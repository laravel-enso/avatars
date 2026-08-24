<?php

namespace LaravelEnso\Avatars;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Avatars\Commands\GenerateAvatars;
use LaravelEnso\Avatars\Observers\User as Observer;
use LaravelEnso\Avatars\Services\LaravoltAvatar;
use LaravelEnso\Users\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->bindLaravolt()
            ->observe()
            ->publish()
            ->commands(GenerateAvatars::class);
    }

    private function bindLaravolt(): self
    {
        App::bind('avatar', function ($app) {
            $avatar = new LaravoltAvatar(
                $app->make('config')->get('laravolt.avatar', []),
                $app->make('cache.store'),
            );

            $avatar->setGenerator($app->make('avatar.generator'));

            return $avatar;
        });

        return $this;
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/avatars.php', 'enso.avatars');

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
            __DIR__.'/../config' => config_path('enso'),
        ], ['avatars-config', 'enso-config']);

        $this->publishes([
            __DIR__.'/../storage/app' => storage_path('app'),
        ], 'avatars-storage');

        return $this;
    }
}
