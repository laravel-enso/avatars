<?php

namespace LaravelEnso\Avatars\Upgrades;

use Illuminate\Support\LazyCollection;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Upgrade\Contracts\MigratesPostDataMigration;

class LegacyAvatars implements MigratesPostDataMigration
{
    public function isMigrated(): bool
    {
        return $this->candidates()->isEmpty();
    }

    public function migratePostDataMigration(): void
    {
        foreach ($this->candidates() as $avatar) {
            $file = $avatar->file;
            $avatar->update(['file_id' => null]);
            $file->delete();
        }
    }

    private function candidates(): LazyCollection
    {
        return Avatar::whereNull('url')
            ->whereHas('file', fn($query) => $query
                ->where('original_name', 'like', 'avatar.%.jpg')
                ->where('mime_type', 'image/jpeg')
                ->whereHas('type', fn($type) => $type->where('model', Avatar::class)))
            ->with('file')
            ->lazyById()
            ->filter(fn (Avatar $avatar) => $avatar->file->original_name === "avatar.{$avatar->user_id}.jpg");
    }
}
