<?php

namespace LaravelEnso\Avatars\App\Services;

use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelEnso\Avatars\App\Models\Avatar;
use LaravelEnso\Core\App\Models\User;
use Laravolt\Avatar\Facade as Service;

class DefaultAvatar
{
    private const Filename = 'avatar';
    private const Extension = 'jpg';
    private const FontSize = 128;

    private $user;
    private $avatar;
    private $filePath;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        DB::transaction(function () {
            $this->avatar = $this->user->avatar()
                ->firstOrCreate(['user_id' => $this->user->id]);

            $this->generate();

            $this->avatar->attach(
                new File($this->filePath()), $this->originalName(), $this->user
            );
        });

        return $this->avatar;
    }

    private function generate()
    {
        $this->avatar->ensureFolderExists();

        Service::create($this->user->person->name)
            ->setDimension(Avatar::Width, Avatar::Height)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->save($this->filePath());
    }

    private function originalName()
    {
        return self::Filename.$this->user->id.'.'.self::Extension;
    }

    private function hashName()
    {
        return Str::random(40).'.'.self::Extension;
    }

    private function filePath()
    {
        return $this->filePath ??= Storage::path(
            $this->avatar->folder().DIRECTORY_SEPARATOR.$this->hashName()
        );
    }

    private function background()
    {
        return (new Collection(
            config('laravolt.avatar.backgrounds')
        ))->random();
    }
}
