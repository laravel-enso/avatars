<?php

namespace LaravelEnso\AvatarManager\app\Models;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\AvatarManager\app\Classes\Storer;
use LaravelEnso\AvatarManager\app\Classes\Destroyer;
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
        return (new Presenter(self::find($id)))->show();
    }

    public function store(array $files)
    {
        return (new Storer($files))->run();
    }

    public function remove()
    {
        (new Destroyer($this))->run();
    }
}
