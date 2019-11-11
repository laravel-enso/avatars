<?php

namespace LaravelEnso\Avatars\app\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Avatars\app\Models\Avatar;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Avatar $avatar)
    {
        $this->authorize('update', $avatar);

        Auth::user()->generateAvatar();

        return ['avatarId' => Auth::user()->avatar->id];
    }
}
