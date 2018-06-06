<?php

namespace LaravelEnso\AvatarManager;

use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\AvatarManager\app\Policies\AvatarPolicy;
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
