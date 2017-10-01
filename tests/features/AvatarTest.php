<?php

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    use RefreshDatabase, SignIn;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        config()->set('enso.config.paths.avatars', 'testFolder');
        $this->signIn(User::first());
    }

    /** @test */
    public function show()
    {
        $this->uploadAvatar();
        $avatar = Avatar::first();

        $this->get('/core/avatars/' . $avatar->id)
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function store()
    {
        $this->uploadAvatar();
        $avatar = Avatar::first();

        $this->assertNotNull($avatar);
        Storage::assertExists('testFolder/' . $avatar->saved_name);

        $this->cleanUp();
    }

    /** @test */
    public function destroy()
    {
        $this->uploadAvatar();
        $avatar = Avatar::first();

        $this->delete(route('core.avatars.destroy', $avatar->id, false));

        Storage::assertMissing('testFolder/' . $avatar->saved_name);
        $this->assertNull($avatar->fresh());

        $this->cleanUp();
    }

    private function uploadAvatar()
    {
        $this->post(route('core.avatars.store', [], false), [
            'file' => UploadedFile::fake()->image('avatar.png'),
        ]);
    }

    private function cleanUp()
    {
        Storage::deleteDirectory('testFolder');
    }
}
