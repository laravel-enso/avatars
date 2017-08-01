<?php

namespace LaravelEnso\AvatarManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Core\app\Models\User;

class Avatar extends Model
{
    protected $fillable = ['user_id', 'original_name', 'saved_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
