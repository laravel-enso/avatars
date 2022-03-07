<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Models\Avatar;
use Throwable;

class Gravatar
{
    private const URL = 'https://www.gravatar.com/avatar';

    public function __construct(private Avatar $avatar)
    {
    }

    public function handle(): ?Avatar
    {
        try {
            $gravatar = Http::head($this->url())->ok();
        } catch (Throwable) {
            return null;
        }

        return $gravatar
            ? $this->gravatar()
            : null;
    }

    private function gravatar(): Avatar
    {
        $this->avatar->fill([
            'url' => $this->url(),
            'file_id' => null,
        ])->save();

        return $this->avatar;
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
            'size' => $this->avatar->imageHeight(),
            'default' => 404,
        ];

        return http_build_query($params);
    }
}
