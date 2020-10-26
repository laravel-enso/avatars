<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Facades\Http;
use LaravelEnso\Avatars\Enums\Types;
use LaravelEnso\Avatars\Models\Avatar;

class Gravatar extends Generator
{
    public function handle(): ?Avatar
    {
        if (Http::head($this->url())->status() === 404) {
            return null;
        }

        $this->avatar->fill([
            'type' => Types::External,
            'url' => $this->url(),
        ])->save();

        return $this->avatar;
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
