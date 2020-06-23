<?php

namespace LaravelEnso\Avatars;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Policies\AvatarPolicy;

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
