<?php

namespace App\Services;

use App\File;
use App\Post;
use App\Traits\UploadFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileService
{
    use UploadFile;

    public function appendFile(FormRequest $data, Post $post)
    {
        if (!Gate::allows('update', $post)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $files = $this->verifyAndUploadFile($data, $post, 'file');
        $images = $this->verifyAndUploadFile($data, $post, 'image');

        return [$files, $images];
    }

    public function deletePostFile(Post $post, File $file)
    {
        if (!Gate::allows('delete', $post)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        DB::transaction(function () use ($file) {
            Storage::disk('public')->delete($file->file);
            $file->delete();
        });
    }


    public function findAllFile(string $directory)
    {
        $files = Storage::disk('public')->allFiles($directory);

        return $files;
    }
}
