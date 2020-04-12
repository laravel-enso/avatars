<?php

namespace LaravelEnso\Avatars\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Avatars\App\Models\Avatar;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Avatar $avatar)
    {
        $this->authorize('update', $avatar);

        $avatar = Auth::user()->generateAvatar();

        return ['avatarId' => $avatar->id];
    }
}
