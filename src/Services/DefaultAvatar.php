<?php

namespace LaravelEnso\Avatars\Services;

use Exception;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\Services\Generators\Gravatar;
use LaravelEnso\Avatars\Services\Generators\Laravolt;
use LaravelEnso\Core\Models\User;

class DefaultAvatar
{
    private const Filename = 'avatar';
    private const Extension = 'jpg';

    private $user;
    private $avatar;
    private $filePath;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        DB::transaction(fn () => $this->findOrCreate()
            ->generate()
            ->attach());

        return $this->avatar;
    }

    private function findOrCreate(): self
    {
        $this->avatar = $this->user->avatar()
            ->firstOrCreate(['user_id' => $this->user->id]);

        return $this;
    }

    private function generate(): self
    {
        try {
            (new Gravatar($this->user))
                ->generate($this->filePath());
        } catch (Exception $e) {
            (new Laravolt($this->user))
                ->generate($this->filePath());
        }

        return $this;
    }

    private function attach(): void
    {
        $avatar = new File($this->filePath());

        $this->avatar->attach($avatar, $this->originalName());
    }

    private function originalName(): string
    {
        return self::Filename.$this->user->id.'.'.self::Extension;
    }

    private function hashName(): string
    {
        return Str::random(40).'.'.self::Extension;
    }

    private function filePath(): string
    {
        return $this->filePath ??= Storage::path(
            $this->avatar->folder().DIRECTORY_SEPARATOR.$this->hashName()
        );
    }
}
