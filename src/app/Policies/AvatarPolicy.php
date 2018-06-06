<?php

namespace LaravelEnso\AvatarManager\app\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\AvatarManager\app\Models\Avatar;

class AvatarPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function create(User $user, Avatar $avatar)
    {
        return !$user->isImpersonating();
    }

    public function destroy(User $user, Avatar $avatar)
    {
        return !$user->isImpersonating()
            && $user->id === $avatar->user_id;
    }
}
