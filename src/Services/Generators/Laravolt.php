<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Models\Avatar;
use Laravolt\Avatar\Avatar as ImageGenerator;
use Laravolt\Avatar\Facade as Service;

class Laravolt
{
    private const FontSize = 128;
    private const Filename = 'avatar';
    private const Extension = 'jpg';
    private string $path;
    private Avatar $avatar;
    private ImageGenerator $generator;

    public function __construct(Avatar $avatar)
    {
        $this->avatar = $avatar;
    }

    public function handle(): ?Avatar
    {
        $this->generate()
            ->persist()
            ->attach();

        return $this->avatar;
    }

    private function generate(): self
    {
        $this->generator = Service::create($this->avatar->user->person->name)
            ->setDimension(Avatar::Width, Avatar::Height)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background());

        return $this;
    }

    private function persist(): self
    {
        $this->generator->save(Storage::path($this->filePath()));

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
        $this->avatar->save();

        $this->avatar->attach(
            $this->filePath(),
            $this->originalName()
        );
    }

    private function originalName(): string
    {
        return self::Filename.$this->avatar->user->id.'.'.self::Extension;
    }
}
