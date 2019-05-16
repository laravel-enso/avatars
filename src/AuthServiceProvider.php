<?php

namespace LaravelEnso\Avatars;

use LaravelEnso\Avatars\app\Models\Avatar;
use LaravelEnso\Avatars\app\Policies\AvatarPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Avatar::class => AvatarPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
