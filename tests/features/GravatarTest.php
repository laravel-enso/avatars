<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use LaravelEnso\Avatars\Services\Generators\Gravatar;
use LaravelEnso\Avatars\Services\Generators\Laravolt;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GravatarTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function default_avatar_prefers_gravatar_and_falls_back_to_svg(): void
    {
        $this->seed();
        $user = User::factory()->create(['is_active' => true]);
        Config::set('enso.avatars.useGravatar', true);
        $environment = $this->app['env'];
        $this->app['env'] = 'local';

        try {
            $status = 200;
            Http::fake(function () use (&$status) {
                return $status === null
                    ? throw new RuntimeException('network down')
                    : Http::response('', $status);
            });
            $avatar = $user->fresh()->generateAvatar();
            $this->assertStringStartsWith('https://www.gravatar.com/avatar/', $avatar->url);
            $this->assertNull($avatar->file_id);

            $status = 404;
            $avatar = $user->fresh()->generateAvatar();
            $this->assertNull($avatar->url);
            $this->assertNull($avatar->file_id);
            $this->assertStringContainsString('<svg', (new Laravolt($avatar))->svg());

            $status = null;
            $avatar = $user->fresh()->generateAvatar();
            $this->assertNull($avatar->url);
            $this->assertNull($avatar->file_id);

            Config::set('enso.avatars.useGravatar', false);
            $status = 200;
            $avatar = $user->fresh()->generateAvatar();
            $this->assertNull($avatar->url);
            $this->assertNull($avatar->file_id);
        } finally {
            $this->app['env'] = $environment;
        }
    }

    #[Test]
    public function returns_null_when_gravatar_probe_throws()
    {
        $this->seed();

        $user = User::factory()->create(['is_active' => true]);

        Http::fake(fn () => throw new RuntimeException('network down'));

        $this->assertNull((new Gravatar($user->fresh()->avatar))->handle());
    }

    #[Test]
    public function persists_remote_url_when_gravatar_exists()
    {
        $this->seed();

        $user = User::factory()->create(['is_active' => true]);

        Http::fake([
            'https://www.gravatar.com/avatar*' => Http::response('', 200),
        ]);

        $avatar = (new Gravatar($user->fresh()->avatar))->handle();

        $this->assertNotNull($avatar);
        $this->assertNull($avatar->file_id);
        $this->assertStringStartsWith('https://www.gravatar.com/avatar/', $avatar->url);
    }
}
