<?php

namespace LaravelEnso\Avatars\App\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Avatars\App\Models\Avatar;

class Show extends Controller
{
    public function __invoke(Avatar $avatar)
    {
        return $avatar->inline();
    }
}
