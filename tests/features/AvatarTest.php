<?php

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\FileManager\app\Classes\FileManager;

class AvatarTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->seed()
            ->actingAs($this->user = User::first());

        $this->user->generateAvatar();
    }

    public function tearDown(): void
    {
        $this->cleanUp();
        parent::tearDown();
    }

    /** @test */
    public function can_display_avatar()
    {
        $this->get(route('core.avatars.show', [$this->user->avatar->id], false))
            ->assertStatus(200)
            ->assertHeader(
                'content-disposition',
                'inline; filename='.$this->user->avatar->file->saved_name
            );
    }

    /** @test */
    public function can_update_avatar()
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
    }

    /** @test */
    public function can_store_avatar()
    {
        $this->user->load('avatar');

        $this->post(route('core.avatars.store', [], false), [
            'avatar' => UploadedFile::fake()->image('avatar.png'),
        ]);

        $this->user->load('avatar');

        $this->assertNotNull($this->user->avatar);

        Storage::assertExists(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$this->user->avatar->file->saved_name
        );
    }

    private function cleanUp()
    {
        $this->user->avatar->delete();

        \Storage::deleteDirectory(FileManager::TestingFolder);
    }
}
