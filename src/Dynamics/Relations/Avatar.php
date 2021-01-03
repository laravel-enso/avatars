<?php

namespace LaravelEnso\Avatars\Dynamics\Relations;

use Closure;
use LaravelEnso\Avatars\Models\Avatar as Model;
use LaravelEnso\DynamicMethods\Contracts\Method;

class Avatar implements Method
{
    public function name(): string
    {
        return 'avatar';
    }

    public function closure(): Closure
    {
        return fn () => $this->hasOne(Model::class);
    }
}
