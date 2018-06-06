<?php

namespace LaravelEnso\AvatarManager\app\Models;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\AvatarManager\app\Classes\Storer;
use LaravelEnso\AvatarManager\app\Classes\Presenter;

class Avatar extends Model
{
    protected $fillable = ['user_id', 'original_name', 'saved_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function show($id)
    {
        return (new Presenter($id))
            ->show();
    }

    public static function store(User $user, array $request)
    {
        return (new Storer($user, $request))
            ->run();
    }
}
