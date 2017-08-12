<?php

namespace LaravelEnso\AvatarManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\FileManager\Classes\FileManager;
use LaravelEnso\ImageTransformer\Classes\ImageTransformer;

class AvatarService
{
    private $request;
    private $fileManager;

    private const ImageHeight = 250;
    private const ImageWidth = 250;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->fileManager = new FileManager(
            config('laravel-enso.paths.avatars'),
            config('laravel-enso.paths.temp')
        );
    }

    public function show($id)
    {
        $avatar = Avatar::find($id);

        return $avatar
            ? $this->fileManager->getInline($avatar->saved_name)
            : $this->fileManager->getInline('profile.png');
    }

    public function store(Avatar $avatar)
    {
        try {
            \DB::transaction(function () use (&$avatar) {
                $file = $this->request->allFiles();
                $this->optimizeImage($file);
                $this->fileManager->startUpload($file);
                $avatar = $this->save($avatar);
                $this->fileManager->commitUpload();
            });
        } catch (\Exception $e) {
            $this->fileManager->deleteTempFiles();

            throw $e;
        }

        return $avatar;
    }

    public function destroy(Avatar $avatar)
    {
        \DB::transaction(function () use ($avatar) {
            $avatar->delete();
            $this->fileManager->delete($avatar->saved_name);
        });
    }

    private function save(Avatar $avatar)
    {
        $attributes = array_merge(
            $this->fileManager->getUploadedFiles()->first(),
            ['user_id' => $this->request->user()->id]
        );

        return $avatar->create($attributes);
    }

    private function optimizeImage($files)
    {
        (new ImageTransformer($files))
            ->resize(self::ImageWidth, self::ImageHeight)
            ->optimize();
    }
}
