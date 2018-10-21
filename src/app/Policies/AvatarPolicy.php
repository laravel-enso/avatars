<?php

namespace LaravelEnso\AvatarManager\app\Policies;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\AvatarManager\app\Models\Avatar;

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
