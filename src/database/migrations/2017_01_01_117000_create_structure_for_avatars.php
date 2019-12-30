<?php

use LaravelEnso\Migrator\App\Database\Migration;
use LaravelEnso\Permissions\App\Enums\Types;

class CreateStructureForAvatars extends Migration
{
    protected $permissions = [
        ['name' => 'core.avatars.update', 'description' => 'Update avatar', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.avatars.show', 'description' => 'Display selected avatar', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.avatars.store', 'description' => 'Upload a new avatar', 'type' => Types::Write, 'is_default' => true],
    ];
}
