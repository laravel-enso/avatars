<?php

namespace LaravelEnso\Avatars;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Avatars\app\Commands\GenerateAvatars;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands(GenerateAvatars::class);

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->publishes([
            __DIR__.'/storage/app' => storage_path('app'),
        ], 'avatars-storage');
    }
}
