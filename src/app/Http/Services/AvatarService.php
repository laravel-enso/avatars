<?php

namespace LaravelEnso\AvatarManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\FileManager\app\Classes\FileManager;
use LaravelEnso\ImageTransformer\Classes\ImageTransformer;

class AvatarService
{
    private $fileManager;

    private const DefaultAvatar = 'profile.png';
    private const ImageHeight = 250;
    private const ImageWidth = 250;

    public function __construct()
    {
        $this->fileManager = new FileManager(config('enso.config.paths.avatars'));
    }

    public function show($id)
    {
        $avatar = Avatar::find($id);

        return $avatar
            ? $this->fileManager->getInline($avatar->saved_name)
            : $this->fileManager->getInline(self::DefaultAvatar);
    }

    public function store(Request $request, Avatar $avatar)
    {
        $this->fileManager->tempPath(config('enso.config.paths.temp'));

        try {
            \DB::transaction(function () use ($request, &$avatar) {
                $file = $request->allFiles();
                $this->optimizeImage($file);
                $this->fileManager->startUpload($file);

                $avatar = $avatar->create(
                    $this->fileManager->uploadedFiles()->first() +
                    ['user_id' => $request->user()->id]
                );

                $this->fileManager->commitUpload();
            });
        } catch (\Exception $exception) {
            $this->fileManager->deleteTempFiles();

            throw $exception;
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

    private function optimizeImage(array $files)
    {
        (new ImageTransformer($files))
            ->resize(self::ImageWidth, self::ImageHeight)
            ->optimize();
    }
}
