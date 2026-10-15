<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Facades\Config;
use LaravelEnso\Avatars\Models\Avatar;
use Laravolt\Avatar\Facade as Service;

class Laravolt
{
    private const FontSize = 128;

    public function __construct(private Avatar $avatar)
    {
    }

    public function handle(): Avatar
    {
        $this->avatar->fill(['url' => null, 'file_id' => null])->save();

        return $this->avatar;
    }

    public function svg(): string
    {
        return Service::create($this->avatar->user->person->name)
            ->setDimension($this->avatar->imageWidth(), $this->avatar->imageHeight())
            ->setFontSize(self::FontSize)
            ->setFontFamily('sans-serif')
            ->setBackground(Config::get('enso.avatars.background'))
            ->toSvg();
    }

}
