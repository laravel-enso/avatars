<?php

namespace LaravelEnso\Avatars\App\Traits;

use LaravelEnso\Avatars\App\Models\Avatar;
use LaravelEnso\Avatars\App\Services\DefaultAvatar;

trait HasAvatar
{
    public static function bootHasAvatar()
    {
        self::created(fn ($model) => $model->generateAvatar());
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class);
    }

    public function generateAvatar(): Avatar
    {
        optional($this->avatar)->delete();

        return (new DefaultAvatar($this))->create();
    }
}
