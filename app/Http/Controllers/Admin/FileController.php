<?php

namespace App\Http\Controllers\Admin;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\File\ImageResource;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }


    public function findAllImages()
    {
        $directory = 'images';

        $images = $this->fileService->findAllFile($directory);

        return response(new ImageResource($images), Response::HTTP_OK);
    }

    public function findAllFiles()
    {
        $directory = 'files';

        $files = $this->fileService->findAllFile($directory);

        return response(new FileResource($files), Response::HTTP_OK);
    }

}
