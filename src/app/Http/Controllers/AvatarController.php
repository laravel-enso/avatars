<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Http\Requests\ValidateAvatarRequest;

class AvatarController extends Controller
{
    public function store(ValidateAvatarRequest $request, Avatar $avatar)
    {
        $this->authorize('create', $avatar);

        return Avatar::store(
            $request->user(),
            $request->allFiles()
        );
    }

    public function show($id)
    {
        return Avatar::show($id);
    }

    public function destroy(Avatar $avatar)
    {
        $this->authorize('destroy', $avatar);

        $user = $avatar->user;
        $avatar->delete();

        return $user->avatarId;
    }
}
