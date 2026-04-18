<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use LaravelEnso\Avatars\Services\Generators\Gravatar;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GravatarTest extends TestCase
{
    use RefreshDatabase;

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
