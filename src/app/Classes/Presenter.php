<?php

namespace LaravelEnso\AvatarManager\app\Classes;

use LaravelEnso\AvatarManager\app\Models\Avatar;

class Presenter extends Handler
{
    private const DefaultAvatar = 'profile.png';

    private $avatar;

    public function __construct(Avatar $avatar = null)
    {
        parent::__construct();

        $this->avatar = $avatar;
    }

    public function show()
    {
        return $this->avatar
            ? $this->fileManager->getInline($this->avatar->saved_name)
            : $this->fileManager->getInline(self::DefaultAvatar);
    }
}
