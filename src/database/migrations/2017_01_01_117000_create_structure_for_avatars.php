<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForAvatars extends StructureMigration
{
    protected $permissionGroup = [
        'name' => 'core.avatars', 'description' => 'Avatars permissions group',
    ];

    protected $permissions = [
        ['name' => 'core.avatars.destroy', 'description' => 'Delete avatar', 'type' => 1, 'is_default' => true],
        ['name' => 'core.avatars.show', 'description' => 'Display selected avatar', 'type' => 0, 'is_default' => true],
        ['name' => 'core.avatars.store', 'description' => 'Upload avatar', 'type' => 1, 'is_default' => true],
    ];
}
