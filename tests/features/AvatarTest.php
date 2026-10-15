<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Format;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Upgrades\LegacyAvatars;
use LaravelEnso\Files\Models\File;
use LaravelEnso\Files\Models\Type;
use LaravelEnso\Helpers\Traits\EnsuresTestingFolder;
use LaravelEnso\Roles\Enums\Roles;
use LaravelEnso\Upgrade\Services\Database as UpgradeRunner;
use LaravelEnso\Upgrade\Services\Package;
use LaravelEnso\Users\Models\User;
use Laravolt\Avatar\Facade as Service;
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
        $this->user->avatar->store(UploadedFile::fake()->image('photo.jpg', 250, 250));

        $this->get(route('core.avatars.show', $this->user->fresh()->avatar, false))
            ->assertStatus(200)
            ->assertHeaderMissing('location');
    }

    #[Test]
    public function redirects_to_avatar_url_when_avatar_is_remote()
    {
        $this->user->avatar->update([
            'url' => 'https://example.test/avatar.png',
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
        $this->user->avatar->store(UploadedFile::fake()->image('photo.jpg', 250, 250));
        $this->user->load('avatar.file');

        $oldFile = $this->user->avatar->file;
        $oldPath = $oldFile->path();

        $this->patch(route('core.avatars.update', $this->user->avatar, false))
            ->assertStatus(200);

        $this->assertFalse(File::query()->whereKey($oldFile->id)->exists());
        Storage::assertMissing($oldPath);

        $avatar = $this->user->fresh()->avatar->load('file');

        $this->assertNull($avatar->file);
        $this->assertNull($avatar->url);
    }

    #[Test]
    public function superior_can_regenerate_another_users_avatar()
    {
        $superior = User::factory()->create([
            'role_id' => Roles::Admin,
            'is_active' => true,
        ]);
        $target = User::factory()->create(['is_active' => true]);

        $target->fresh()->avatar->store(UploadedFile::fake()->image('target.jpg', 250, 250));

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
        $this->assertSame($oldFileId, $avatar->fresh()->file_id);
        $this->assertNull($avatar->fresh()->file_id);
    }

    #[Test]
    public function does_not_recreate_the_testing_folder_when_generating_an_avatar()
    {
        Storage::deleteDirectory(Config::get('enso.files.testingFolder'));

        $avatar = $this->user->generateAvatar();

        $this->assertInstanceOf(Avatar::class, $avatar);
        $this->assertNull($avatar->fresh()->file);
        Storage::assertMissing(Config::get('enso.files.testingFolder'));
    }

    #[Test]
    public function creates_default_avatar_when_user_is_created()
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertNotNull($user->fresh()->avatar);
        $this->assertNull($user->fresh()->avatar->file);
        $this->assertNull($user->fresh()->avatar->url);
        $this->assertNull($user->fresh()->avatar->file_id);
    }

    #[Test]
    public function creates_default_avatar_when_a_user_is_created_after_the_testing_folder_was_deleted()
    {
        Storage::deleteDirectory(Config::get('enso.files.testingFolder'));

        $user = User::factory()->create(['is_active' => true]);

        $this->assertNotNull($user->fresh()->avatar);
        $this->assertNull($user->fresh()->avatar->file);
        $this->assertNull($user->fresh()->avatar->file_id);
    }

    #[Test]
    public function serves_stable_svg_with_escaped_initials_without_writing_files(): void
    {
        Config::set('laravolt.avatar.backgrounds', ['#ff0000', '#00ff00']);
        $this->user->person->update(['name' => '< &']);
        $files = File::count();
        $stored = Storage::allFiles();
        $url = route('core.avatars.show', $this->user->avatar, false);

        $response = $this->get($url)->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml')
            ->assertHeaderMissing('location');
        $svg = $response->getContent();
        $this->assertSame($svg, $this->get($url)->getContent());
        $document = new DOMDocument();
        $this->assertTrue($document->loadXML($svg));
        $this->assertSame('<&', $document->getElementsByTagName('text')->item(0)->textContent);
        $this->assertSame($files, File::count());
        $this->assertSame($stored, Storage::allFiles());

        $this->user->person->update(['name' => 'Ada Lovelace']);
        $updated = $this->get($url)->getContent();
        $this->assertStringContainsString('>AL</text>', $updated);
        $background = $document->getElementsByTagName('rect')->item(0)->getAttribute('fill');
        $this->assertSame('#64748b', $background);
        $this->assertTrue($document->loadXML($updated));
        $this->assertSame($background, $document->getElementsByTagName('rect')->item(0)->getAttribute('fill'));

        $other = User::factory()->create(['is_active' => true]);
        $otherSvg = $this->get(route('core.avatars.show', $other->fresh()->avatar, false))
            ->assertOk()->getContent();
        $this->assertTrue($document->loadXML($otherSvg));
        $this->assertSame($background, $document->getElementsByTagName('rect')->item(0)->getAttribute('fill'));

        Config::set('enso.avatars.background', '#334155');
        $this->assertTrue($document->loadXML($this->get($url)->assertOk()->getContent()));
        $this->assertSame('#334155', $document->getElementsByTagName('rect')->item(0)->getAttribute('fill'));
    }

    #[Test]
    public function preserves_legacy_generated_avatar_without_reclassification(): void
    {
        $avatar = $this->user->avatar;
        $name = 'legacy-generated.jpg';
        $path = Type::for(Avatar::class)->path($name);
        $image = Service::create($this->user->person->name)
            ->setDimension(250)->getImageObject()
            ->encodeUsingFormat(Format::JPEG)->toString();
        Storage::put($path, $image);
        $file = File::attach($avatar, $name, "avatar.{$this->user->id}.jpg");
        $avatar->update(['file_id' => $file->id]);

        $response = $this->get(route('core.avatars.show', $avatar, false))->assertOk();

        $this->assertSame($image, $response->streamedContent());
        $this->assertSame($file->id, $avatar->fresh()->file_id);
        Storage::assertExists($path);
    }

    #[Test]
    public function preserves_files_with_legacy_generated_names(): void
    {
        $avatar = $this->user->avatar;
        $avatar->store(UploadedFile::fake()->image("avatar.{$this->user->id}.jpg", 250, 250));
        $file = $avatar->fresh()->file;
        $contents = Storage::get($file->path());

        $response = $this->get(route('core.avatars.show', $avatar, false))->assertOk();

        $this->assertSame($contents, $response->streamedContent());
        $this->assertSame($file->id, $avatar->fresh()->file_id);
        Storage::assertExists($file->path());
        $this->artisan('enso:avatars:generate')->assertSuccessful();
        $this->assertSame($file->id, $avatar->fresh()->file_id);
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
        $this->assertNull($user->fresh()->avatar->file);
    }

    #[Test]
    public function upgrade_removes_legacy_candidates_and_is_repeatable(): void
    {
        $avatar = $this->user->avatar;
        $file = $this->legacyFile($avatar);
        $path = $file->path();
        $upgrade = new LegacyAvatars();

        $this->assertTrue((new Package(dirname(__DIR__, 2)))->upgradeClasses()->contains(LegacyAvatars::class));
        $this->assertFalse($upgrade->isMigrated());
        (new UpgradeRunner($upgrade))->handle();

        $this->assertNull($avatar->fresh()->file_id);
        $this->assertNull($avatar->fresh()->url);
        $this->assertModelMissing($file);
        Storage::assertMissing($path);
        $this->get(route('core.avatars.show', $avatar, false))
            ->assertOk()->assertHeader('Content-Type', 'image/svg+xml');
        $this->assertTrue($upgrade->isMigrated());
        (new UpgradeRunner($upgrade))->handle();
        $this->assertTrue($upgrade->isMigrated());
    }

    #[Test]
    public function upgrade_preserves_non_candidates_and_gravatar(): void
    {
        $avatars = collect();

        foreach (['photo.jpg', 'avatar.999999.jpg', 'avatar.1.png'] as $filename) {
            $avatar = User::factory()->create(['is_active' => true])->fresh()->avatar;
            $avatar->store(UploadedFile::fake()->image($filename, 250, 250));
            $avatars->push($avatar->fresh());
        }

        $remote = User::factory()->create(['is_active' => true])->fresh()->avatar;
        $this->legacyFile($remote);
        $remote->update(['url' => 'https://www.gravatar.com/avatar/test']);
        $avatars->push($remote->fresh());

        $wrongMime = User::factory()->create(['is_active' => true])->fresh()->avatar;
        $this->legacyFile($wrongMime)->update(['mime_type' => 'image/png']);
        $avatars->push($wrongMime->fresh());

        $upgrade = new LegacyAvatars();
        $this->assertTrue($upgrade->isMigrated());
        $upgrade->migratePostDataMigration();

        foreach ($avatars as $avatar) {
            $this->assertSame($avatar->file_id, $avatar->fresh()->file_id);
            $this->assertSame($avatar->url, $avatar->fresh()->url);
            Storage::assertExists($avatar->file->path());
        }
    }

    #[Test]
    public function upgrade_removes_candidate_records_when_file_is_already_missing(): void
    {
        $avatar = $this->user->avatar;
        $file = $this->legacyFile($avatar);
        Storage::delete($file->path());

        (new LegacyAvatars())->migratePostDataMigration();

        $this->assertNull($avatar->fresh()->file_id);
        $this->assertModelMissing($file);
    }

    #[Test]
    public function upgrade_continues_when_storage_deletion_returns_false(): void
    {
        $avatar = $this->user->avatar;
        $file = $this->legacyFile($avatar);
        $path = $file->path();
        $storage = Storage::getFacadeRoot();
        Storage::shouldReceive('has')->andReturnTrue();
        Storage::shouldReceive('delete')->once()->with($path)->andReturnFalse();

        try {
            (new LegacyAvatars())->migratePostDataMigration();
        } finally {
            Storage::swap($storage);
        }

        $this->assertNull($avatar->fresh()->file_id);
        $this->assertModelMissing($file);
        Storage::assertExists($path);
        $this->assertTrue((new LegacyAvatars())->isMigrated());
        Storage::delete($path);
    }

    private function legacyFile(Avatar $avatar): File
    {
        $name = "legacy-{$avatar->id}.jpg";
        $path = Type::for(Avatar::class)->path($name);
        $image = Service::create($avatar->user->person->name)
            ->setDimension(250)->getImageObject()
            ->encodeUsingFormat(Format::JPEG)->toString();
        Storage::put($path, $image);
        $file = File::attach($avatar, $name, "avatar.{$avatar->user_id}.jpg");
        $avatar->update(['file_id' => $file->id]);

        return $file;
    }
}
