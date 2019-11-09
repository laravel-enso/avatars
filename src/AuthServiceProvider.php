<?php

namespace LaravelEnso\Avatars;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Avatars\app\Models\Avatar;
use LaravelEnso\Avatars\app\Policies\AvatarPolicy;

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
