<?php

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\FileManager\app\Classes\FileManager;

class AvatarTest extends TestCase
{
    use RefreshDatabase, SignIn;

    private $user;

    protected function setUp()
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->seed()
            ->signIn($this->user = User::first());

        $this->user->generateAvatar();
    }

    /** @test */
    public function show()
    {
        $this->get('/core/avatars/'.$this->user->avatar->id)
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function update()
    {
        $this->user->load('avatar.file');

        $oldAvatar = $this->user->avatar;

        $this->patch(route('core.avatars.update', $this->user->avatar->id, false));

        Storage::assertMissing(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$oldAvatar->file->saved_name
        );

        $this->assertNotNull($this->user->avatar->fresh());

        $this->assertNotEquals($oldAvatar->id, $this->user->avatar->id);

        Storage::assertExists(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$this->user->avatar->file->saved_name
        );

        $this->cleanUp();
    }

    /** @test */
    public function store()
    {
        $this->user->load('avatar');

        $this->uploadAvatar();

        $this->user->load('avatar');

        $this->assertNotNull($this->user->avatar);

        Storage::assertExists(FileManager::TestingFolder.DIRECTORY_SEPARATOR.$this->user->avatar->file->saved_name);

        $this->cleanUp();
    }

    private function uploadAvatar()
    {
        $this->post(route('core.avatars.store', [], false), [
            'avatar' => UploadedFile::fake()->image('avatar.png'),
        ]);
    }

    private function cleanUp()
    {
        $this->user->avatar->delete();

        \Storage::deleteDirectory(FileManager::TestingFolder);
    }
}
