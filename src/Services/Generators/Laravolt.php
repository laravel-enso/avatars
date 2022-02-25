<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Files\Models\File;
use LaravelEnso\Files\Models\Type;
use Laravolt\Avatar\Facade as Service;

class Laravolt
{
    private const FontSize = 128;
    private const Filename = 'avatar';
    private const Extension = 'jpg';
    private string $hashName;

    public function __construct(
        private Avatar  $avatar,
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
            ->setDimension($this->avatar->imageWidth(), $this->avatar->imageHeight())
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->save(Storage::path($this->path()));

        return $this;
    }

    private function persist(): void
    {
        $file = File::attach($this->avatar, $this->hashName(), $this->filename());

        $this->avatar->fill([
            'url' => null,
            'file_id' => $file->id,
        ])->save();
    }

    private function background(): string
    {
        return Collection::wrap(Config::get('laravolt.avatar.backgrounds'))->random();
    }

    private function path(): string
    {
        $folder = Type::for(Avatar::class)->folder;

        return $this->path ??= "{$folder}/{$this->hashName()}";
    }

    private function hashName(): string
    {
        return $this->hashName ??= Str::of('.')
            ->prepend(Str::random(40))
            ->append(self::Extension)
            ->__toString();
    }

    private function filename(): string
    {
        $filename = self::Filename;
        $extension = self::Extension;

        return "{$filename}.{$this->avatar->user->id}.{$extension}";
    }
}
