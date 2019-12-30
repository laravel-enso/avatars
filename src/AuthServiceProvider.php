<?php

namespace LaravelEnso\Avatars;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Avatars\App\Models\Avatar;
use LaravelEnso\Avatars\App\Policies\AvatarPolicy;

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
