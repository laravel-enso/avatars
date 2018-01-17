<?php

namespace LaravelEnso\AvatarManager\app\Classes;

class Presenter extends Handler
{
    private $image;

    public function __construct(string $image)
    {
        parent::__construct();

        $this->image = $image;
    }

    public function show()
    {
        return $this->fileManager->getInline($this->image);
    }
}
