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

    public function generateAvatar()
    {
        optional($this->avatar)->delete();

        (new DefaultAvatar($this))->create();

        $this->load('avatar');
    }
}
