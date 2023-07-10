<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotFoundCustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\FindPostRequest;
use App\Http\Resources\PostResource;
use App\Post;
use App\Services\PostService;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function findAllPosts(FindPostRequest $findPostRequest)
    {
        $posts = $this->postService->findAllPosts($findPostRequest);

        return response(new PostResource($posts), Response::HTTP_OK);
    }

    public function findByPost(Post $post)
    {
        $findByPost = $this->postService->findByPost($post);

        return response(new PostResource($findByPost), Response::HTTP_OK);
    }

    public function disablePost(Post $post): \Illuminate\Http\JsonResponse
    {
        $this->postService->updateStatePost($post, 0);

        return response()->json(['data' => ['message' => '게시글이 비활성화가 되었습니다.']], Response::HTTP_OK);
    }

    public function enablePost(Post $post): \Illuminate\Http\JsonResponse
    {
        $this->postService->updateStatePost($post, 1);

        return response()->json(['data' => ['message' => '게시글이 활성화가 되었습니다.']], Response::HTTP_OK);
    }
}
