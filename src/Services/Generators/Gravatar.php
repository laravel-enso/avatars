<?php

namespace LaravelEnso\Avatars\Services\Generators;

use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Core\Models\User;

class Gravatar
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function generate($filePath)
    {
        file_put_contents(
            $filePath,
            file_get_contents($this->url())
        );
    }

    private function hash()
    {
        return md5(strtolower($this->user->email));
    }

    protected function url(): string
    {
        return 'https://www.gravatar.com/avatar/' . $this->hash() .
            '?d=404&s=' . Avatar::Height;
    }
}
