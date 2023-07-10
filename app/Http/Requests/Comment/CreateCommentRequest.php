<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'required|max:255',
            'image' => 'size:1',
            'image.*' => 'mimes:jpeg,png,jpg',
        ];
    }
}
