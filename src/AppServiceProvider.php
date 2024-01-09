<?php

namespace LaravelEnso\Avatars;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Avatars\Commands\GenerateAvatars;
use LaravelEnso\Avatars\Observers\User as Observer;
use LaravelEnso\Users\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->observe()
            ->publish()
            ->commands(GenerateAvatars::class);
    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        return $this;
    }

    private function observe()
    {
        App::make(User::class)::observe(Observer::class);

        return $this;
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/../storage/app' => storage_path('app'),
        ], 'avatars-storage');

        return $this;
    }
}
