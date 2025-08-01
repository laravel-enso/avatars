<?php

namespace LaravelEnso\Avatars\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAvatar extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return ['avatar' => 'required|image:allow_svg|dimensions:ratio=1'];
    }
}
