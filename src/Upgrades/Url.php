<?php

namespace LaravelEnso\Avatars\Upgrades;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaravelEnso\Upgrade\Contracts\MigratesTable;

class Url implements MigratesTable
{
    public function migrateTable(): void
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->string('url')->nullable()->after('user_id');
        });
    }

    public function isMigrated(): bool
    {
        return Schema::hasColumn('avatars', 'url');
    }
}
