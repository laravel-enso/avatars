<?php

use LaravelEnso\Core\app\Classes\StructureManager\StructureMigration;

class CreateStructureForAvatars extends StructureMigration
{
    protected $permissionGroup = [
        'name' => 'core.avatars', 'description' => 'Avatars permissions group',
    ];

    protected $permissions = [
        ['name' => 'core.avatars.destroy', 'description' => 'Delete avatar', 'type' => 1, 'default' => true],
        ['name' => 'core.avatars.show', 'description' => 'Display selected avatar', 'type' => 0, 'default' => true],
        ['name' => 'core.avatars.store', 'description' => 'Upload avatar', 'type' => 1, 'default' => true],
    ];
}
