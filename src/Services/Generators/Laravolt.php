<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Avatars\Models\Avatar;
use Laravolt\Avatar\Facade as Service;

class Laravolt extends Generator
{
    private const FontSize = 128;

    public function generate(): File
    {
        Service::create($this->avatar->user->person->name)
            ->setDimension(Avatar::Width, Avatar::Height)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->save(Storage::path($this->filePath()));

        return new File(Storage::path($this->filePath()));
    }

    private function background(): string
    {
        return (new Collection(
            config('laravolt.avatar.backgrounds')
        ))->random();
    }
}
