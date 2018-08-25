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
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class);
    }

    public function getAvatarIdAttribute()
    {
        $avatar = $this->avatar
            ?? $this->generateAvatar();

        unset($this->avatar);

        return $avatar->id;
    }

    public function generateAvatar()
    {
        optional($this->avatar)->delete();

        return (new DefaultAvatar($this))
            ->create();
    }
}
