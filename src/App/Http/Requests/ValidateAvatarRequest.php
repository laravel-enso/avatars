<?php

namespace LaravelEnso\Avatars\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAvatarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return ['avatar' => 'required|image'];
    }
}
