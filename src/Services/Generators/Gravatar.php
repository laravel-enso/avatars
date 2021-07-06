<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Models\Avatar;

class Gravatar
{
    private const URL = 'https://www.gravatar.com/avatar';

    public function __construct(private Avatar $avatar)
    {
    }

    public function handle(): ?Avatar
    {
        return Http::head($this->url())->ok()
            ? tap($this->avatar->fill(['url' => $this->url()]))->save()
            : null;
    }

    private function url(): string
    {
        $url = self::URL;

        return "$url/{$this->hash()}?{$this->query()}";
    }

    private function hash(): string
    {
        return md5(Str::lower($this->avatar->user->email));
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
