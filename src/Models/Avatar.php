<?php

namespace LaravelEnso\Avatars\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Core\Models\User;
use LaravelEnso\Files\Contracts\Attachable;
use LaravelEnso\Files\Traits\HasFile;
use LaravelEnso\Helpers\Traits\CascadesMorphMap;

class Avatar extends Model implements Attachable
{
    use CascadesMorphMap, HasFile;

    public const Width = 250;
    public const Height = 250;

    protected $guarded = ['id'];

    protected $hidden = ['user_id', 'created_at', 'updated_at'];

    protected $casts = ['user_id' => 'integer'];

    protected $optimizeImages = true;

    protected $resizeImages = [
        'width' => self::Width,
        'height' => self::Height,
    ];

    protected $mimeTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];

    protected $folder = 'avatars';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store(UploadedFile $file)
    {
        return DB::transaction(function () use ($file) {
            $this->delete();
            $avatar = Auth::user()->avatar()->create();
            $avatar->file->upload($file);

            return $avatar;
        });
    }
}
