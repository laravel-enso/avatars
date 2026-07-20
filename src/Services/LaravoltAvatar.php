<?php

namespace LaravelEnso\Avatars\Services;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;
use Laravolt\Avatar\Avatar;

class LaravoltAvatar extends Avatar
{
    public function buildAvatar(): static
    {
        $this->buildInitial();

        $driver = $this->driver === 'gd' ? new GdDriver() : new ImagickDriver();
        $this->image = ImageManager::usingDriver($driver)
            ->createImage($this->width, $this->height);

        $this->createShape();

        if (empty($this->initials)) {
            return $this;
        }

        $this->image->text(
            $this->initials,
            (int) ($this->width / 2),
            (int) ($this->height / 2),
            function (FontFactory $font) {
                $font->filepath($this->font);
                $font->size($this->fontSize);
                $font->color($this->foreground);
                $font->align(Alignment::CENTER, Alignment::CENTER);
            }
        );

        return $this;
    }
}
