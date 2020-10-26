<?php

namespace LaravelEnso\Avatars\Services\Generators;

use LaravelEnso\Avatars\Models\Avatar;

class Generator
{
    protected Avatar $avatar;

    public function __construct(Avatar $avatar)
    {
        $this->avatar = $avatar;
    }
}
