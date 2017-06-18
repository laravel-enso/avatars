<?php

namespace LaravelEnso\AvatarManager;

use Illuminate\Support\ServiceProvider;

class AvatarManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->publishes([
            __DIR__.'/resources/storage/app' => storage_path('app'),
        ], 'avatars-storage');
    }

    public function register()
    {
        //
    }
}
