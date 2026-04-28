<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'contents.*.title_kh' => 'required|string|max:255',
            'contents.*.content_kh' => 'required|string',
            'contents.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        ];
    }
}
