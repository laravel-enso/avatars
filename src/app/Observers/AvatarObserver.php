<?php

namespace LaravelEnso\AvatarManager\app\Observers;

use LaravelEnso\AvatarManager\app\Classes\Destroyer;

class AvatarObserver
{
    public function deleting($avatar)
    {
        (new Destroyer($avatar))->run();
    }
}
