<?php

namespace LaravelEnso\AvatarManager\app\Commands;

use App\User;
use Illuminate\Console\Command;

class GenerateAvatars extends Command
{
    protected $signature = 'enso:avatars:generate';

    protected $description = 'Generates missing avatars for users';

    public function handle()
    {
        auth()->onceUsingId(User::first()->id);

        User::doesntHave('avatar')
            ->get()
            ->each
            ->generateAvatar();

        $this->info('Avatars generated successfully');
    }
}
