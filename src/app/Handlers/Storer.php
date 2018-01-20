<?php

namespace LaravelEnso\AvatarManager\app\Handlers;

use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\ImageTransformer\Classes\ImageTransformer;

class Storer extends Handler
{
    private const ImageHeight = 250;
    private const ImageWidth = 250;

    private $file;
    private $avatar;

    public function __construct(array $file)
    {
        parent::__construct();

        $this->fileManager->tempPath(config('enso.config.paths.temp'));

        $this->file = $file;
    }

    public function run()
    {
        $this->upload();

        return $this->avatar;
    }

    private function upload()
    {
        try {
            \DB::transaction(function () {
                $this->optimizeImage($this->file);
                $this->fileManager->startUpload($this->file);
                $this->avatar = $this->store();
                $this->fileManager->commitUpload();
            });
        } catch (\Exception $exception) {
            $this->fileManager->deleteTempFiles();
            throw $exception;
        }
    }

    private function store()
    {
        return Avatar::create(
            $this->fileManager->uploadedFiles()->first() +
            ['user_id' => auth()->user()->id]
        );
    }

    private function optimizeImage()
    {
        (new ImageTransformer($this->file))
            ->resize(self::ImageWidth, self::ImageHeight)
            ->optimize();
    }
}
