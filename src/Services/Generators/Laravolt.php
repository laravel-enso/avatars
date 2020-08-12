<?php

namespace LaravelEnso\Avatars\Services\Generators;

use Illuminate\Support\Collection;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Core\Models\User;
use Laravolt\Avatar\Facade as Service;

class Laravolt
{
    private const FontSize = 128;
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function generate($filePath)
    {
        Service::create($this->user->person->name)
            ->setDimension(Avatar::Width, Avatar::Height)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->save($filePath);
    }

    private function background(): string
    {
        return (new Collection(
            config('laravolt.avatar.backgrounds')
        ))->random();
    }
}
