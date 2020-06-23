<?php

namespace LaravelEnso\Avatars\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\Models\Avatar;

class Show extends Controller
{
    public function __invoke(Avatar $avatar)
    {
        return $avatar->inline();
    }
}
