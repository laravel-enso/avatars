<?php

namespace LaravelEnso\Avatars\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Files\app\Contracts\Attachable;
use LaravelEnso\Files\app\Traits\HasFile;

class Avatar extends Model implements Attachable
{
    use HasFile;

    const Width = 250;
    const Height = 250;

    protected $fillable = ['user_id', 'original_name', 'saved_name'];

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    protected $casts = ['user_id' => 'int'];

    protected $optimizeImages = true;

    protected $resizeImages = [
        'width' => self::Width,
        'height' => self::Height,
    ];

    protected $mimeTypes = ['image/png', 'image/jpg', 'image/jpeg'];

    protected $folder = 'avatars';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store(UploadedFile $file)
    {
        $avatar = null;

        DB::transaction(function () use (&$avatar, $file) {
            $this->delete();

            $avatar = self::create(['user_id' => Auth::user()->id]);

            $avatar->upload($file);
        });

        return $avatar;
    }
}
