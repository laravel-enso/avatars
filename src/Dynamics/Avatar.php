<?php

namespace LaravelEnso\Avatars\Dynamics;

use Closure;
use LaravelEnso\Avatars\Models\Avatar as Model;
use LaravelEnso\DynamicMethods\Contracts\Relation;
use LaravelEnso\Users\Models\User;

class Avatar implements Relation
{
    public function bindTo(): array
    {
        return [User::class];
    }

    public function name(): string
    {
        return 'avatar';
    }

    public function closure(): Closure
    {
        return fn (User $user) => $user->hasOne(Model::class);
    }
}
