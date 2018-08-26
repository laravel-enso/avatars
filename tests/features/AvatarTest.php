<?php

use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use LaravelEnso\FileManager\app\Classes\FileManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $this->get('/core/avatars/'.$this->user->avatarId)
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function update()
    {
        $this->user->load('avatar');

        $this->patch(route('core.avatars.update', $this->user->avatar->id, false));

        Storage::assertMissing(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$this->user->avatar->file->saved_name
        );

        $this->assertNull($this->user->avatar->fresh());

        unset($this->user->avatar);

        $this->assertNotNull($this->user->avatar);

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
