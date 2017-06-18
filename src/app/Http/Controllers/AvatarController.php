<?php

namespace LaravelEnso\AvatarManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\AvatarManager\app\Http\Services\AvatarService;
use LaravelEnso\AvatarManager\app\Models\Avatar;

class AvatarController extends Controller
{
    private $avatars;

    public function __construct(Request $request)
    {
        $this->avatars = new AvatarService($request);
    }

    // public function store(ValidateAvatarRequest $request, Avatar $avatar) //fixme
    public function store(Avatar $avatar)
    {
        return $this->avatars->store($avatar);
    }

    public function show($id)
    {
        return $this->avatars->show($id);
    }

    public function destroy(Avatar $avatar)
    {
        $this->authorize('updateProfile', $avatar->user);
        $this->avatars->destroy($avatar);
    }
}
