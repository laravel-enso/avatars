<?php

namespace LaravelEnso\AvatarManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\FileManager\Classes\FileManager;

class AvatarService
{
	private $request;
	private $fileManager;

    public function __construct(Request $request)
    {
    	$this->request = $request;
        $this->fileManager = new FileManager(config('laravel-enso.paths.avatars'), config('laravel-enso.paths.temp'));
    }

    public function store()
    {
        $avatar = null;

        \DB::transaction(function () use (&$avatar) {
            $this->fileManager->startUpload($this->request->all());
            $avatar = $this->saveNewAvatar();
            $this->fileManager->commitUpload();
        });

        return $avatar;
    }

    public function show($avatarId)
    {
        $avatar = Avatar::find($avatarId);

        return $avatar
            ? $this->fileManager->getInline($avatar->original_name, $avatar->saved_name)
            : $this->fileManager->getInline('profile.png', 'profile.png');
    }

    public function destroy(Avatar $avatar)
    {
        \DB::transaction(function () use ($avatar) {
            $avatar->delete();
            $this->fileManager->delete($avatar->saved_name);
        });
    }

    private function checkOldAvatar()
    {
    	$oldAvatar = Avatar::whereUserId($this->request->user()->id)->first();

        if ($oldAvatar) {
            $this->fileManager->delete($oldAvatar->saved_name);
        }
    }

    private function saveNewAvatar()
    {
    	$attributes = array_merge(
    		$this->fileManager->getUploadedFiles()->first(),
    		['user_id' => $this->request->user()->id]
    	);

        return Avatar::create($attributes);
    }
}