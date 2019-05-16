<?php

namespace LaravelEnso\Avatars\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\app\Models\Avatar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Avatar $avatar)
    {
        $this->authorize('update', $avatar);

        auth()->user()->generateAvatar();

        return ['avatarId' => auth()->user()->avatar->id];
    }
}
