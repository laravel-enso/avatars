<?php

namespace LaravelEnso\Avatars\Traits;

use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Services\DefaultAvatar;

trait HasAvatar
{
    public static function bootHasAvatar()
    {
        self::created(fn ($model) => $model->generateAvatar());
    }

    public function generateAvatar(): Avatar
    {
        optional($this->avatar)->delete();

        return (new DefaultAvatar($this))->create();
    }
}
