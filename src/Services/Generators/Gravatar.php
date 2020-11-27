<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Avatars\Models\Avatar;

class Gravatar extends Generator
{
    public function generate(): ?string
    {
        if (Http::head($this->url())->status() === 404) {
            return null;
        }

        Storage::put($this->filePath(), file_get_contents($this->url()));

        return $this->filePath();
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
