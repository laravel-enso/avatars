<?php

namespace LaravelEnso\Avatars\Observers;

use LaravelEnso\Users\Models\User as Model;

class User
{
    public function created(Model $user)
    {
        $user->generateAvatar();
    }
}
