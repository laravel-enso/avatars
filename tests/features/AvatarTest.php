<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Files\Models\File;
use LaravelEnso\Helpers\Traits\EnsuresTestingFolder;
use LaravelEnso\Roles\Enums\Roles;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    use EnsuresTestingFolder;
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ensureTestingFolder();

        $this->seed()
            ->actingAs($this->user = User::first());
    }

    protected function tearDown(): void
    {
        File::query()->get()
            ->each(fn (File $file) => Storage::delete($file->path()));

        parent::tearDown();
    }

    #[Test]
    public function shows_file_backed_avatar_inline()
    {
        $this->get(route('core.avatars.show', $this->user->avatar, false))
            ->assertStatus(200)
            ->assertHeaderMissing('location');
    }

    #[Test]
    public function redirects_to_avatar_url_when_avatar_is_remote()
    {
        $this->user->avatar->update([
            'url'     => 'https://example.test/avatar.png',
            'file_id' => null,
        ]);

        $this->get(route('core.avatars.show', $this->user->avatar, false))
            ->assertRedirect('https://example.test/avatar.png');
    }

    #[Test]
    public function can_store_avatar()
    {
        $response = $this->post(route('core.avatars.store', [], false), [
            'avatar' => UploadedFile::fake()->image('avatar.png', 512, 512),
        ]);

        $response->assertStatus(200);

        $avatar = $this->user->fresh()->avatar->load('file');

        $this->assertNotNull($avatar);
        $this->assertNull($avatar->url);
        $this->assertNotNull($avatar->file);
        Storage::assertExists($avatar->file->path());
    }

    #[Test]
    public function validates_uploaded_avatar_as_square_image()
    {
        $this->post(route('core.avatars.store', [], false), [
            'avatar' => UploadedFile::fake()->image('avatar.png', 320, 200),
        ])->assertStatus(302)
            ->assertSessionHasErrors(['avatar']);
    }

    #[Test]
    public function can_update_avatar_and_remove_previous_file()
    {
        $this->user->load('avatar.file');

        $oldFile = $this->user->avatar->file;
        $oldPath = $oldFile->path();

        $this->patch(route('core.avatars.update', $this->user->avatar, false))
            ->assertStatus(200);

        $this->assertFalse(File::query()->whereKey($oldFile->id)->exists());
        Storage::assertMissing($oldPath);

        $avatar = $this->user->fresh()->avatar->load('file');

        $this->assertNotNull($avatar->file);
        $this->assertNull($avatar->url);
        $this->assertNotSame($oldFile->id, $avatar->file_id);
        Storage::assertExists($avatar->file->path());
    }

    #[Test]
    public function superior_can_regenerate_another_users_avatar()
    {
        $superior = User::factory()->create([
            'role_id'   => Roles::Admin,
            'is_active' => true,
        ]);
        $target = User::factory()->create(['is_active' => true]);

        $superiorOldFileId = $superior->fresh()->avatar->file_id;
        $targetOldFileId = $target->fresh()->avatar->file_id;

        $this->actingAs($superior)
            ->patch(route('core.avatars.update', $target->fresh()->avatar, false))
            ->assertStatus(200);

        $this->assertNotSame($targetOldFileId, $target->fresh()->avatar->file_id);
        $this->assertSame($superiorOldFileId, $superior->fresh()->avatar->file_id);
    }

    #[Test]
    public function impersonating_user_cannot_update_avatar()
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->withSession(['impersonating' => $this->user->id])
            ->patch(route('core.avatars.update', $user->fresh()->avatar, false))
            ->assertForbidden();
    }

    #[Test]
    public function exposes_avatar_relation_on_user()
    {
        $this->assertInstanceOf(Avatar::class, $this->user->avatar);
        $this->assertTrue($this->user->avatar()->exists());
    }

    #[Test]
    public function exposes_generate_avatar_method_on_user()
    {
        $oldFileId = $this->user->avatar->file_id;

        $avatar = $this->user->generateAvatar();

        $this->assertInstanceOf(Avatar::class, $avatar);
        $this->assertNotSame($oldFileId, $avatar->fresh()->file_id);
    }

    #[Test]
    public function recreates_the_testing_folder_when_generating_an_avatar()
    {
        Storage::deleteDirectory(Config::get('enso.files.testingFolder'));

        $avatar = $this->user->generateAvatar();

        $this->assertInstanceOf(Avatar::class, $avatar);
        $this->assertNotNull($avatar->fresh()->file);
        Storage::assertExists($avatar->fresh()->file->path());
    }

    #[Test]
    public function creates_default_avatar_when_user_is_created()
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertNotNull($user->fresh()->avatar);
        $this->assertNotNull($user->fresh()->avatar->file);
        $this->assertNull($user->fresh()->avatar->url);
        Storage::assertExists($user->fresh()->avatar->file->path());
    }

    #[Test]
    public function creates_default_avatar_when_a_user_is_created_after_the_testing_folder_was_deleted()
    {
        Storage::deleteDirectory(Config::get('enso.files.testingFolder'));

        $user = User::factory()->create(['is_active' => true]);

        $this->assertNotNull($user->fresh()->avatar);
        $this->assertNotNull($user->fresh()->avatar->file);
        Storage::assertExists($user->fresh()->avatar->file->path());
    }

    #[Test]
    public function generates_missing_avatars_via_command()
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->fresh()->avatar->delete();

        $this->assertNull($user->fresh()->avatar);

        $this->artisan('enso:avatars:generate')
            ->expectsOutput('Avatars generated successfully')
            ->assertSuccessful();

        $this->assertNotNull($user->fresh()->avatar);
        $this->assertNotNull($user->fresh()->avatar->file);
    }
}
