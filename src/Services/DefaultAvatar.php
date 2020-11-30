<?php

namespace LaravelEnso\Avatars\Services;

use Illuminate\Http\File;
use Illuminate\Support\Facades\App;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Services\Generators\Gravatar;
use LaravelEnso\Avatars\Services\Generators\Laravolt;
use LaravelEnso\Core\Models\User;

class DefaultAvatar
{
    private const Filename = 'avatar';
    private const Extension = 'jpg';

    private $user;
    private $avatar;
    private File $file;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(): Avatar
    {
        return $this->findOrNew()->generate();
    }

    private function findOrNew(): self
    {
        $this->avatar = $this->user->avatar()
            ->firstOrNew(['user_id' => $this->user->id]);

        return $this;
    }

    private function generate(): Avatar
    {
        return App::runningUnitTests()
            ? (new Laravolt($this->avatar))->handle()
            : (new Gravatar($this->avatar))->handle()
            ?? (new Laravolt($this->avatar))->handle();
    }
}
