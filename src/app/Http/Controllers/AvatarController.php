<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Handlers\Storer;
use LaravelEnso\AvatarManager\app\Handlers\Destroyer;
use LaravelEnso\AvatarManager\app\Handlers\Presenter;
use LaravelEnso\AvatarManager\app\Http\Requests\ValidateAvatarRequest;

class AvatarController extends Controller
{
    public function store(ValidateAvatarRequest $request, Avatar $avatar)
    {
        return (new Storer($request->allFiles()))->run();
    }

    public function show($id)
    {
        return (new Presenter($id))->show();
    }

    public function destroy(Avatar $avatar)
    {
        $this->authorize('destroy-avatar', $avatar->user);

        (new Destroyer($avatar))->run();
    }
}
