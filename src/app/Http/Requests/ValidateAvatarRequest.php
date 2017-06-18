<?php

namespace LaravelEnso\AvatarManager\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAvatarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file_0' => 'image|dimensions:min_width=100,min_height=100,max_heigth=500,max_width=500',
        ];
    }
}
