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

    public function __construct(private Avatar $avatar)
    {
    }

    public function handle(): ?Avatar
    {
        $this->generate()
            ->persist();

        return $this->avatar;
    }

    private function generate(): self
    {
        $this->save();

        if (!Storage::exists($this->path())) {
            $this->save();
        }

        return $this;
    }

    private function save(): void
    {
        $this->ensureDirectoryExists();

        $image = Service::create($this->avatar->user->person->name)
            ->setDimension($this->avatar->imageWidth(), $this->avatar->imageHeight())
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->getImageObject()
            ->toJpeg()
            ->toString();

        Storage::put($this->path(), $image);
    }

    private function persist(): void
    {
        $file = File::attach($this->avatar, $this->hashName(), $this->filename());

        $this->avatar->fill([
            'url'     => null,
            'file_id' => $file->id,
        ])->save();
    }

    private function background(): string
    {
        return Collection::wrap(Config::get('laravolt.avatar.backgrounds'))->random();
    }

    private function path(): string
    {
        return Type::for(Avatar::class)->path($this->hashName());
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

    private function ensureDirectoryExists(): void
    {
        $directory = dirname($this->path());

        if ($directory !== '.' && !Storage::has($directory)) {
            Storage::makeDirectory($directory);
        }
    }
}
