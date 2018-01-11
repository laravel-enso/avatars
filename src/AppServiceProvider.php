<?php

namespace LaravelEnso\AvatarManager;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->publishes([
            __DIR__.'/storage/app' => storage_path('app'),
        ], 'avatars-storage');
    }

    public function register()
    {
        //
    }
}
