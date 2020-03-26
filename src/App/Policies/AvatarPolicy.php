<?php

namespace LaravelEnso\Avatars\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Avatars\App\Models\Avatar;
use LaravelEnso\Core\App\Models\User;

class AvatarPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function update(User $user, Avatar $avatar)
    {
        return ! $user->isImpersonating()
            && $user->id === $avatar->user_id;
    }
}
