<?php

namespace LaravelEnso\AvatarManager;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Gate::define('destroy-avatar', function ($user, $profileUser) {
            return $user->id === $profileUser->id;
        });
    }
}
