<?php

namespace LaravelEnso\Avatars\app\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\app\Http\Requests\ValidateAvatarRequest;

class Store extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateAvatarRequest $request)
    {
        $avatar = $request->user()->avatar;

        $this->authorize('update', $avatar);

        return $avatar->store($request->file('avatar'));
    }
}
