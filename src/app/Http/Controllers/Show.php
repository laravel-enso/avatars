<?php

namespace LaravelEnso\Avatars\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\app\Models\Avatar;

class Show extends Controller
{
    public function __invoke(Avatar $avatar)
    {
        return $avatar->inline();
    }
}
