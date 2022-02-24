<?php

namespace LaravelEnso\Avatars\Dynamics\Methods;

use Closure;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Services\DefaultAvatar;
use LaravelEnso\DynamicMethods\Contracts\Method;

class GenerateAvatar implements Method
{
    public function name(): string
    {
        return 'generateAvatar';
    }

    public function closure(): Closure
    {
        return fn (): Avatar => (new DefaultAvatar($this))->create();
    }
}
