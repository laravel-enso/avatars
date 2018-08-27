<?php

namespace LaravelEnso\AvatarManager\app\Traits;

use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Classes\DefaultAvatar;

trait HasAvatar
{
    public static function bootHasAvatar()
    {
        self::created(function ($model) {
            $model->generateAvatar();
        });

        self::deleting(function ($model) {
            $model->avatar->delete();
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
