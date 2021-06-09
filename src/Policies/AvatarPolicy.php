<?php

namespace LaravelEnso\Avatars\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Users\Models\User;

class AvatarPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }
    }

    public function update(User $user, Avatar $avatar): bool
    {
        return ! $user->isImpersonating() && $user->id === $avatar->user_id;
    }
}
