<?php

namespace LaravelEnso\Avatars\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\Enums\Types;
use LaravelEnso\Avatars\Models\Avatar;

class Show extends Controller
{
    public function __invoke(Avatar $avatar)
    {
        return $avatar->type === Types::File
            ? $avatar->inline()
            : redirect($avatar->url);
    }
}
