<?php

namespace LaravelEnso\AvatarManager\app\Classes;

use LaravelEnso\AvatarManager\app\Models\Avatar;

class Presenter extends Handler
{
    private const DefaultAvatar = 'profile.png';

    private $image;

    public function __construct(int $avatarId)
    {
        parent::__construct();

        $avatar = Avatar::find($avatarId);

        $this->image = $avatar
            ? $avatar->saved_name
            : self::DefaultAvatar;
    }

    public function show()
    {
        return $this->fileManager
            ->inline($this->image);
    }
}
