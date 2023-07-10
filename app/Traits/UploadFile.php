<?php

namespace App\Traits;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadFile
{
    public function verifyAndUploadFile(FormRequest $request, Model $model, $inputType): ?array
    {
        if (!$request->hasfile($inputType)) {
            return null;
        }

        foreach ($request->file($inputType) as $file) {
            $uploadFile = Storage::disk('public')->put($this->getPath($inputType), $file);
            $createFile[] = $this->createFile($model, $inputType, $uploadFile, $file);
        }

        return $createFile;
    }

    public function deleteFileByModel(Model $model)
    {
        /** @var Post|Comment|User $model */
        foreach ($model->files()->get() as $file) {
            Storage::disk('public')->delete($file->file);
        }
        /* foreach ($model->images()->get() as $file) {
             Storage::disk('public')->delete($file->file);
         }*/

        $model->files()->delete();
        //$model->images()->delete();
    }

    protected function createFile(Model $model, string $inputType, string $uploadFile, UploadedFile $file)
    {
        return $model->files()->create([
            'type' => $inputType,
            'file' => $uploadFile,
            'origin_file' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'fileable_type' => $model->getMorphClass(),
            'fileable_id' => $model->id,
        ]);
    }

    protected function getPath($inputType)
    {
        switch ($inputType) {
            case 'file':
                return 'files';
            case 'image':
                return 'images';
            default:
                return '';
        }
    }
}
