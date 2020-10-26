<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Enums\Types;
use LaravelEnso\Avatars\Models\Avatar;
use Laravolt\Avatar\Facade as Service;

class Laravolt extends Generator
{
    private const FontSize = 128;
    private const Filename = 'avatar';
    private const Extension = 'jpg';
    private string $path;

    public function handle(): ?Avatar
    {
        $this->generate()->attach();

        return $this->avatar;
    }

    public function generate(): self
    {
        Service::create($this->avatar->user->person->name)
            ->setDimension(Avatar::Width, Avatar::Height)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->save(Storage::path($this->filePath()));

        return $this;
    }

    private function filePath(): string
    {
        return $this->path
            ??= $this->avatar->folder().DIRECTORY_SEPARATOR.$this->hashName();
    }

    private function hashName(): string
    {
        return Str::random(40).'.'.static::Extension;
    }

    private function background(): string
    {
        return (new Collection(
            config('laravolt.avatar.backgrounds')
        ))->random();
    }

    private function attach(): void
    {
        $this->avatar->fill([
            'type' => Types::File
        ])->save();

        $this->avatar->attach(
            new File(Storage::path($this->filePath())),
            $this->originalName()
        );
    }

    private function originalName(): string
    {
        return self::Filename.$this->avatar->user->id.'.'.self::Extension;
    }
}
