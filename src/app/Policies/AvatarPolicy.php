<?php

namespace LaravelEnso\Avatars\app\Policies;

use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Avatars\app\Models\Avatar;
use Illuminate\Auth\Access\HandlesAuthorization;

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
