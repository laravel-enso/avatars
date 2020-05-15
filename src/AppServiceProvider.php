<?php

namespace LaravelEnso\Avatars;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Avatars\App\Commands\GenerateAvatars;
use LaravelEnso\Avatars\App\Models\Avatar;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->mapMorphs()
            ->publish()
            ->commands(GenerateAvatars::class);
    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        return $this;
    }

    private function mapMorphs()
    {
        Relation::morphMap([
            Avatar::morphMapKey() => Avatar::class,
        ]);

        return $this;
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/storage/app' => storage_path('app'),
        ], 'avatars-storage');

        return $this;
    }
}
