<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\AvatarManager\app\Http\Requests\ValidateAvatarRequest;

class AvatarController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function store(ValidateAvatarRequest $request)
    {
        $avatar = $request->user()->avatar;

        $this->authorize('update', $avatar);

        return $avatar->store($request->file('avatar'));
    }

    public function show(Avatar $avatar)
    {
        return $avatar->inline();
    }

    public function update(Avatar $avatar)
    {
        $this->authorize('update', $avatar);

        auth()->user()->generateAvatar();

        return ['avatarId' => auth()->user()->avatar->id];
    }
}
