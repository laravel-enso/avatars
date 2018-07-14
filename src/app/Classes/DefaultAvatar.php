<?php

namespace LaravelEnso\AvatarManager\app\Classes;

use LaravelEnso\Core\app\Models\User;
use LaravelEnso\AvatarManager\app\Models\Avatar;

class DefaultAvatar
{
    private const Filename = 'avatar';
    private const Extension = '.jpg';
    private const FontSize = 128;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        $this->generate();

        return Avatar::create([
            'user_id' => $this->user->id,
            'original_name' => $this->filename().self::Extension,
            'saved_name' => $this->hash().self::Extension,
        ]);
    }

    private function generate()
    {
        \Avatar::create($this->user->fullName)
            ->setDimension(Storer::ImageWidth, Storer::ImageHeight)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->getImageObject()
            ->save($this->path($this->hash()));
    }

    private function hash()
    {
        return $this->hash
            ?? $this->hash = uniqid($this->filename());
    }

    private function filename()
    {
        return self::Filename.$this->user->id;
    }

    private function path()
    {
        return storage_path(
            'app/'.config('enso.config.paths.avatars').'/'
            .$this->hash().self::Extension
        );
    }

    private function background()
    {
        return collect(config('laravolt.avatar.backgrounds'))
            ->random();
    }
}
