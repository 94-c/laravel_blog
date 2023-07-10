<?php

namespace App\Http\Requests\Post;

use App\Post;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'body' => 'required|max:255',
            'image.*' => 'mimes:jpeg,png,jpg',
            'file.*' => 'mimes:txt,pdf,doc,xls,xlm,',
        ];
    }
}
