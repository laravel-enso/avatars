<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Models\Avatar;

class Gravatar
{
    public function __construct(private Avatar $avatar)
    {
    }

    public function handle(): ?Avatar
    {
        if (Http::head($this->url())->ok()) {
            $this->avatar->fill(['url' => $this->url()])->save();

            return $this->avatar;
        }

        return null;
    }

    private function url(): string
    {
        return "https://www.gravatar.com/avatar/{$this->hash()}?{$this->query()}";
    }

    private function hash(): string
    {
        return md5(Str::of($this->avatar->user->email)->lower());
    }

    private function query(): string
    {
        $params = [
            'size' => Avatar::Height,
            'default' => 404,
        ];

        return http_build_query($params);
    }
}
