<?php

namespace LaravelEnso\Avatars\app\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Files\app\Traits\HasFile;
use LaravelEnso\Files\app\Contracts\Attachable;

class Avatar extends Model implements Attachable
{
    use HasFile;

    const ImageWidth = 250;
    const ImageHeight = 250;

    protected $fillable = ['user_id', 'original_name', 'saved_name'];

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    protected $casts = ['user_id' => 'int'];

    protected $optimizeImages = true;

    protected $resizeImages = [
        'width' => self::ImageWidth,
        'height' => self::ImageHeight,
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

            $avatar = Avatar::create(['user_id' => auth()->user()->id]);

            $avatar->upload($file);
        });

        return $avatar;
    }
}
