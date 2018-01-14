<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Http\Services\AvatarService;
use LaravelEnso\AvatarManager\app\Http\Requests\ValidateAvatarRequest;

class AvatarController extends Controller
{
    public function store(ValidateAvatarRequest $request, Avatar $avatar, AvatarService $service)
    {
        return $service->store($request, $avatar);
    }

    public function show($id, AvatarService $service)
    {
        return $service->show($id);
    }

    public function destroy(Avatar $avatar, AvatarService $service)
    {
        $this->authorize('destroy-avatar', $avatar->user);

        $service->destroy($avatar);
    }
}
