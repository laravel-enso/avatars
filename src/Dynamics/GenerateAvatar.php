<?php

namespace LaravelEnso\Avatars\Dynamics;

use Closure;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Services\DefaultAvatar;
use LaravelEnso\DynamicMethods\Contracts\Method;
use LaravelEnso\Users\Models\User;

class GenerateAvatar implements Method
{
    public function bindTo(): array
    {
        return [User::class];
    }

    public function name(): string
    {
        return 'generateAvatar';
    }

    public function closure(): Closure
    {
        return fn (): Avatar => (new DefaultAvatar($this))->create();
    }
}
