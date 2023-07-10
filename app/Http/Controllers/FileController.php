<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\File\AppendFileRequest;
use App\Http\Requests\File\DownloadFileRequest;
use App\Http\Resources\File\FileResource;
use App\Post;
use App\Services\FileService;
use App\Traits\UploadFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function appendFile(AppendFileRequest $appendFileRequest, Post $post)
    {
        $files = $this->fileService->appendFile($appendFileRequest, $post);

        return response(new FileResource($files), Response::HTTP_CREATED);
    }

    //TODO api에서는 get 방식으로 다운로드는 안되나, 웹 브라우저에서는 다운로드가 가능 해짐
    public function downloadFile(DownloadFileRequest $downloadFileRequest)
    {
        $file = $downloadFileRequest->query('file');
        $fileName = $downloadFileRequest->query('origin');

        if (!Storage::disk('public')->exists($file)) {
            return response(['data' => ['message' => '존재하지 않는 파일입니다.']], Response::HTTP_NOT_FOUND);
        }

        return response(new FileResource(['downloadFile' => $fileName]), Response::HTTP_OK)
            ->download(storage_path() . '/app/public/' . $file, $fileName);
    }

    public function deleteFile(Post $post, File $file)
    {
        $this->fileService->deletePostFile($post, $file);

        return response()->json(['data' => ['message' => '파일이 삭제되었습니다.']], Response::HTTP_OK);
    }
}
