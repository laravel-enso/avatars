<?php

namespace LaravelEnso\AvatarManager\app\Models;

use Illuminate\Http\UploadedFile;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\FileManager\app\Traits\HasFile;
use LaravelEnso\FileManager\app\Contracts\Attachable;

class Avatar extends Model implements Attachable
{
    use HasFile;

    const ImageWidth = 250;
    const ImageHeight = 250;

    protected $fillable = ['user_id', 'original_name', 'saved_name'];

    protected $folder = 'avatars';
    protected $optimizeImages = true;
    protected $resizeImages = [self::ImageWidth, self::ImageHeight];
    protected $mimeTypes = ['image/png', 'image/jpg'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store(UploadedFile $file)
    {
        $this->delete();

        $avatar = Avatar::create(['user_id' => auth()->user()->id]);

        $avatar->upload($file);

        return $avatar;
    }
}
