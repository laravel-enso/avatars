<?php

namespace LaravelEnso\AvatarManager\app\Classes;

use LaravelEnso\AvatarManager\app\Models\Avatar;

class Presenter extends Handler
{
    private $avatar;

    public function __construct($avatarId)
    {
        parent::__construct();

        $this->avatar = Avatar::find($avatarId);
    }

    public function show()
    {
        return $this->fileManager
            ->inline($this->avatar->saved_name);
    }
}
