<?php

namespace LaravelEnso\Avatars;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Avatars\Commands\GenerateAvatars;
use LaravelEnso\Avatars\Dynamics\Methods\GenerateAvatar;
use LaravelEnso\Avatars\Dynamics\Relations\Avatar as Relation;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Observers\User as Observer;
use LaravelEnso\DynamicMethods\Services\Methods;
use LaravelEnso\Users\Models\User;

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

        Methods::bind(User::class, [Relation::class, GenerateAvatar::class]);

        // User::observe(Observer::class);
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
