<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class DownloadFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required',
            'origin' => 'required',
        ];
    }
}
