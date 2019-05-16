<?php

namespace LaravelEnso\Avatars\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\app\Models\Avatar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\Avatars\app\Http\Requests\ValidateAvatarRequest;

class Store extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateAvatarRequest $request, Avatar $avatar)
    {
        $avatar = $avatar->whereUserId($request->user()->id)
            ->first();

        $this->authorize('update', $avatar);

        return $avatar->store($request->file('avatar'));
    }
}
