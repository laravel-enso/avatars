<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Files\Models\File;
use LaravelEnso\Users\Models\User;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs($this->user = User::first());

        $this->createTestFolder();
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
            ->assertStatus(200);
    }

    /** @test */
    public function can_update_avatar()
    {
        $this->user->load('avatar.file');

        $oldFile = $this->user->avatar->file;

        $this->patch(route('core.avatars.update', $this->user->avatar->id, false));

        $this->assertTrue(File::whereId($oldFile->id)->doesntExist());

        unset($this->user->avatar);

        $this->assertNotNull($this->user->avatar->file);
        Storage::assertExists($this->user->avatar->file->path());
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

        $path = Config::get('enso.files.testingFolder').'/'.
            $this->user->avatar->file->saved_name;

        Storage::assertExists($path);
    }

    private function createTestFolder()
    {
        if (! Storage::has(Config::get('enso.files.testingFolder'))) {
            Storage::makeDirectory(Config::get('enso.files.testingFolder'));
        }
    }

    private function cleanUp()
    {
        $this->user->avatar->delete();

        Storage::deleteDirectory(Config::get('enso.files.testingFolder'));
    }
}
