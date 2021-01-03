<?php

namespace LaravelEnso\Avatars\Observers;

use LaravelEnso\Core\Models\User;

class Observer
{
    public function created(User $user)
    {
        $user->generateAvatar();
    }
}
