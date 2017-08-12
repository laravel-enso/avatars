<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\AvatarManager\app\Models\Avatar;
use LaravelEnso\TestHelper\app\Classes\TestHelper;

class AvatarTest extends TestHelper
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        // $this->disableExceptionHandling();
        config()->set('laravel-enso.paths.avatars', 'testFolder');
        $this->signIn(User::first());
    }

    /** @test */
    public function show()
    {
        $this->uploadAvatar();
        $avatar = Avatar::first();

        $this->get('/core/avatars/'.$avatar->id)
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function store()
    {
        $this->uploadAvatar();
        $avatar = Avatar::first();

        $this->assertNotNull($avatar);
        Storage::assertExists('testFolder/'.$avatar->saved_name);

        $this->cleanUp();
    }

    /** @test */
    public function destroy()
    {
        $this->uploadAvatar();
        $avatar = Avatar::first();

        $this->delete('/core/avatars/'.$avatar->id);

        Storage::assertMissing('testFolder/'.$avatar->saved_name);
        $this->assertNull($avatar->fresh());

        $this->cleanUp();
    }

    private function uploadAvatar()
    {
        $this->post('/core/avatars/', [
                'file' => UploadedFile::fake()->image('avatar.png'),
        ]);
    }

    private function cleanUp()
    {
        Storage::deleteDirectory('testFolder');
    }
}
