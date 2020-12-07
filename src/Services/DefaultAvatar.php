<?php

namespace LaravelEnso\Avatars\Services;

use Illuminate\Support\Facades\App;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Services\Generators\Gravatar;
use LaravelEnso\Avatars\Services\Generators\Laravolt;
use LaravelEnso\Core\Models\User;

class DefaultAvatar
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(): Avatar
    {
        return App::runningUnitTests()
            ? $this->laravolt()
            : $this->gravatar()
            ?? $this->laravolt();
    }

    private function laravolt()
    {
        return (new Laravolt($this->avatar()))->handle();
    }

    private function gravatar()
    {
        return (new Gravatar($this->avatar()))->handle();
    }

    private function avatar(): Avatar
    {
        return $this->user->avatar()->firstOrNew();
    }
}
