<?php

namespace LaravelEnso\Avatars\Observers;

use LaravelEnso\Users\Models\User;

class Observer
{
    public function created(User $user)
    {
        $user->generateAvatar();
    }
}
