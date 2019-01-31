<?php

namespace LaravelEnso\AvatarManager\app\Models;

use Illuminate\Http\UploadedFile;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\FileManager\app\Traits\HasFile;
use LaravelEnso\FileManager\app\Contracts\Attachable;
use LaravelEnso\Multitenancy\app\Traits\SystemConnection;

class Avatar extends Model implements Attachable
{
    use HasFile, SystemConnection;

    const ImageWidth = 250;
    const ImageHeight = 250;

    protected $fillable = ['user_id', 'original_name', 'saved_name'];

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    protected $casts = ['user_id' => 'int'];

    protected $optimizeImages = true;

    protected $resizeImages = [self::ImageWidth, self::ImageHeight];

    protected $mimeTypes = ['image/png', 'image/jpg', 'image/jpeg'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store(UploadedFile $file)
    {
        $avatar = null;

        \DB::transaction(function () use (&$avatar, $file) {
            $this->delete();

            $avatar = Avatar::create(['user_id' => auth()->user()->id]);

            $avatar->upload($file);
        });

        return $avatar;
    }

    public function folder()
    {
        return config('enso.config.paths.avatars');
    }
}
