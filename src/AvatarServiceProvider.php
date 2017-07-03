<?php

namespace LaravelEnso\AvatarManager;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\ImageTransformer\ImageTransformerServiceProvider;

class AvatarServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->publishes([
            __DIR__.'/storage/app'                     => storage_path('app'),
            __DIR__.'/storage/app/avatars/profile.png' => storage_path('app/avatars/profile.png'),
        ], 'avatars-storage');
    }

    public function register()
    {
        $this->app->register(ImageTransformerServiceProvider::class);
    }
}
