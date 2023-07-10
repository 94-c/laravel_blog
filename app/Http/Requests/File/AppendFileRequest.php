<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppendFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image.*' => 'mimes:jpeg,png,jpg',
            'file.*' => 'mimes:txt,pdf,doc,xls,xlm,',
        ];
    }
}
