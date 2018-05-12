<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Http\Requests\ValidateAvatarRequest;

class AvatarController extends Controller
{
    public function store(ValidateAvatarRequest $request, Avatar $avatar)
    {
        return Avatar::store($request->allFiles());
    }

    public function show($id)
    {
        return Avatar::show($id);
    }

    public function destroy(Avatar $avatar)
    {
        $this->authorize('destroy-avatar', $avatar->user);

        $avatar->delete();
    }
}
