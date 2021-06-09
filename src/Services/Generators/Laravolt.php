<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Models\Avatar;
use Laravolt\Avatar\Facade as Service;

class Laravolt
{
    private const FontSize = 128;
    private const Filename = 'avatar';
    private const Extension = 'jpg';

    public function __construct(
        private Avatar $avatar,
        private ?string $path = null,
    ) {
    }

    public function handle(): ?Avatar
    {
        $this->generate()
            ->persist();

        return $this->avatar;
    }

    private function generate(): self
    {
        Service::create($this->avatar->user->person->name)
            ->setDimension(Avatar::Width, Avatar::Height)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->save(Storage::path($this->path()));

        return $this;
    }

    private function persist(): self
    {
        $this->avatar->save();
        $this->avatar->file->attach($this->path(), $this->filename());

        return $this;
    }

    private function background(): string
    {
        return Collection::wrap(Config::get('laravolt.avatar.backgrounds'))->random();
    }

    private function path(): string
    {
        return $this->path
            ??= "{$this->avatar->folder()}/{$this->hashName()}";
    }

    private function hashName(): string
    {
        $hash = Str::random(40);
        $extension = self::Extension;

        return "{$hash}.{$extension}";
    }

    private function filename(): string
    {
        $filename = self::Filename;
        $extension = self::Extension;

        return "{$filename}.{$this->avatar->user->id}.{$extension}";
    }
}
