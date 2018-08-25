<?php

namespace LaravelEnso\AvatarManager\app\Classes;

use LaravelEnso\Core\app\Models\User;

class DefaultAvatar
{
    private const Filename = 'avatar';
    private const Extension = '.jpg';
    private const FontSize = 128;

    private $user;
    private $avatar;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        $this->generate();

        \DB::transaction(function () {
            $this->avatar = $this->user->avatar()
                ->firstOrcreate(['user_id' => $this->user->id]);

            $this->avatar->file()->create([
                'original_name' => $this->filename(),
                'saved_name' => $this->hashName(),
                'size' => \File::size($this->savePath()),
                'mime_type' => \File::mimeType($this->savePath())
            ]);
        });

        return $this->avatar;
    }

    private function generate()
    {
        \Avatar::create($this->user->fullName)
            ->setDimension(Storer::ImageWidth, Storer::ImageHeight)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->getImageObject()
            ->save($this->savePath());
    }

    private function hashName()
    {
        return $this->hashName
            ?? $this->hashName = uniqid(self::Filename.$this->user->id).self::Extension;
    }

    private function filename()
    {
        return self::Filename.$this->user->id.self::Extension;
    }

    private function savePath()
    {
        return storage_path(
            'app/'.config('enso.config.paths.avatars').'/'
            .$this->hashName()
        );
    }

    private function background()
    {
        return collect(config('laravolt.avatar.backgrounds'))
            ->random();
    }
}
