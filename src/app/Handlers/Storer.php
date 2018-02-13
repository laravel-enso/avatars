<?php

namespace LaravelEnso\AvatarManager\app\Handlers;

use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\ImageTransformer\app\Classes\ImageTransformer;

class Storer extends Handler
{
    private const ImageHeight = 250;
    private const ImageWidth = 250;

    private $file;

    public function __construct(array $file)
    {
        parent::__construct();

        $this->fileManager->tempPath(config('enso.config.paths.temp'));

        $this->file = $file;
    }

    public function run()
    {
        $avatar = null;

        try {
            \DB::transaction(function () use (&$avatar) {
                $this->processImage();
                $this->fileManager->startUpload($this->file);
                $avatar = $this->store();
                $this->fileManager->commitUpload();
            });
        } catch (\Exception $exception) {
            // $this->fileManager->deleteTempFiles();
            throw $exception;
        }

        return $avatar;
    }

    private function store()
    {
        return Avatar::create(
            $this->fileManager->uploadedFiles()->first() +
            ['user_id' => auth()->user()->id]
        );
    }

    private function processImage()
    {
        (new ImageTransformer(collect($this->file)->first()))
            ->resize(self::ImageWidth, self::ImageHeight)
            ->optimize();
    }
}
