<?php

namespace LaravelEnso\Avatars\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\Models\Avatar;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Avatar $avatar)
    {
        $this->authorize('update', $avatar);

        $avatar = $request->user()->generateAvatar();
    }
}
