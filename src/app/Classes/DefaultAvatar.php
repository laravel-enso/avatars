<?php

namespace LaravelEnso\AvatarManager\app\Classes;

use LaravelEnso\Core\app\Models\User;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\FileManager\app\Classes\FileManager;

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
            $this->avatar->file()->create($this->attributes());
        });

        return $this->avatar;
    }

    private function generate()
    {
        \Avatar::create($this->user->fullName)
            ->setDimension(Avatar::ImageWidth, Avatar::ImageHeight)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->getImageObject()
            ->save($this->savePath());
    }

    private function attributes()
    {
        return [
            'original_name' => $this->filename(),
            'saved_name' => $this->hashName(),
            'size' => \File::size($this->savePath()),
            'mime_type' => \File::mimeType($this->savePath()),
        ];
    }

    private function filename()
    {
        return self::Filename.$this->user->id.self::Extension;
    }

    private function hashName()
    {
        return $this->hashName
            ?? $this->hashName = uniqid(self::Filename.$this->user->id).self::Extension;
    }

    private function savePath()
    {
        $folder = app()->environment() === 'testing'
            ? FileManager::TestingFolder
            : config('enso.config.paths.avatars');

        if (!\Storage::has($folder)) {
            \Storage::makeDirectory($folder);
        }

        return storage_path('app/'.$folder.'/'.$this->hashName());
    }

    private function background()
    {
        return collect(config('laravolt.avatar.backgrounds'))
            ->random();
    }
}
