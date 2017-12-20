<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Http\Services\AvatarService;
use LaravelEnso\AvatarManager\app\Http\Requests\ValidateAvatarRequest;

class AvatarController extends Controller
{
    private $service;

    public function __construct(AvatarService $service)
    {
        $this->service = $service;
    }

    public function store(ValidateAvatarRequest $request, Avatar $avatar)
    {
        return $this->service->store($request, $avatar);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function destroy(Avatar $avatar)
    {
        $this->authorize('update-profile', $avatar->user);
        $this->service->destroy($avatar);
    }
}
