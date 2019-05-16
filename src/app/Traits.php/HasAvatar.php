<?php

namespace LaravelEnso\Avatars\app\Traits;

use LaravelEnso\Avatars\app\Models\Avatar;
use LaravelEnso\Avatars\app\Services\DefaultAvatar;

trait HasAvatar
{
    public static function bootHasAvatar()
    {
        self::created(function ($model) {
            $model->generateAvatar();
        });
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class);
    }

    public function generateAvatar()
    {
        optional($this->avatar)->delete();

        (new DefaultAvatar($this))->create();

        $this->load('avatar');
    }
}
