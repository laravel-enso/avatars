<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Avatars\Models\Avatar;

class Gravatar extends Generator
{
    public function generate(): File
    {
        Storage::put($this->filePath(), file_get_contents($this->url()));

        return new File(Storage::path($this->filePath()));
    }

    private function url(): string
    {
        return "https://www.gravatar.com/avatar/{$this->hash()}?".http_build_query($this->params());
    }

    private function hash(): string
    {
        return md5(strtolower($this->avatar->user->email));
    }

    private function params(): array
    {
        return [
            'size' => Avatar::Height,
            'default' => 404,
        ];
    }
}
