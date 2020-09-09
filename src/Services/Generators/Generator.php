<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Str;
use LaravelEnso\Avatars\Models\Avatar;

class Generator
{
    protected $path;
    protected Avatar $avatar;

    public function __construct(Avatar $avatar)
    {
        $this->avatar = $avatar;
    }

    protected function filePath(): string
    {
        return $this->path
            ??= $this->avatar->folder().DIRECTORY_SEPARATOR.$this->hashName();
    }

    protected function hashName(): string
    {
        return Str::random(40).'.jpg';
    }
}
