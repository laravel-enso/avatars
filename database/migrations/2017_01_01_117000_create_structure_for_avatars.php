<?php

use LaravelEnso\Migrator\Database\Migration;

return new class() extends Migration {
    protected array $permissions = [
        ['name' => 'core.avatars.update', 'description' => 'Update avatar', 'is_default' => true],
        ['name' => 'core.avatars.show', 'description' => 'Display selected avatar', 'is_default' => true],
        ['name' => 'core.avatars.store', 'description' => 'Upload a new avatar', 'is_default' => true],
    ];
};
