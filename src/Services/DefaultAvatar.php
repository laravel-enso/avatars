<?php

namespace LaravelEnso\Avatars\Services;

use Illuminate\Support\Facades\App;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Services\Generators\Gravatar;
use LaravelEnso\Avatars\Services\Generators\Laravolt;
use LaravelEnso\Users\Models\User;

class DefaultAvatar
{
    public function __construct(private User $user)
    {
    }

    public function create(): Avatar
    {
        $oldFile = $this->user->avatar?->file;

        $avatar = App::runningUnitTests()
            ? $this->laravolt()
            : $this->gravatar() ?? $this->laravolt();

        $oldFile?->delete();

        return $avatar;
    }

    private function laravolt(): Avatar
    {
        return (new Laravolt($this->avatar()))->handle();
    }

    private function gravatar(): ?Avatar
    {
        return (new Gravatar($this->avatar()))->handle();
    }

    private function avatar(): Avatar
    {
        return $this->user->avatar()->firstOrNew();
    }
}
