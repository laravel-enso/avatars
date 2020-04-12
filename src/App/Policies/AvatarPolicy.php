<?php

namespace LaravelEnso\Avatars\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Avatars\App\Models\Avatar;
use LaravelEnso\Core\App\Models\User;

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
