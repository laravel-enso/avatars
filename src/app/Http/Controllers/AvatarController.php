<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Http\Requests\ValidateAvatarRequest;

class AvatarController extends Controller
{
    public function store(ValidateAvatarRequest $request)
    {
        $avatar = auth()->user()->avatar;

        $this->authorize('store', $avatar);

        return $avatar->store($request->file('avatar'));
    }

    public function show(Avatar $avatar)
    {
        return $avatar->inline();
    }

    public function update(Avatar $avatar)
    {
        $this->authorize('update', $avatar);

        $avatar = auth()->user()->generateAvatar();

        return ['avatarId' => $avatar->id];
    }
}
