<?php

namespace LaravelEnso\Avatars;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Avatars\Commands\GenerateAvatars;
use LaravelEnso\Avatars\DynamicsRelations\Avatar as Relation;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Core\Models\User;
use LaravelEnso\DynamicMethods\Services\Methods;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->relations()
            ->publish()
            ->commands(GenerateAvatars::class);
    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        return $this;
    }

    private function relations()
    {
        Avatar::morphMap();
        Methods::bind(User::class, [Relation::class]);

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
