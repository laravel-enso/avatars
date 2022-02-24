<?php

namespace LaravelEnso\Avatars\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAvatarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return ['avatar' => 'required|image|dimensions:ratio=1'];
    }
}
