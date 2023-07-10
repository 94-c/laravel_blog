<?php

namespace App\Http\Controllers;

use App\Helpers\CacheHelper;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\FindPostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Post;
use App\Services\PostService;
use App\Traits\ConnectionWithTag;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    private PostService $postService;
    private CacheHelper $cache;

    public function __construct(PostService $postService)
    {
        $this->cache = new CacheHelper('file');
        $this->postService = $postService;
    }

    public function findAllPosts(FindPostRequest $findPostRequest)
    {
        $posts = $this->postService->findAllPosts($findPostRequest);

        $this->cache->setCached('find-all-posts' . $posts, $posts->toJson());

        return response(new PostResource($posts), Response::HTTP_OK);
    }


    public function createPost(CreatePostRequest $createPostRequest)
    {
        $post = $this->postService->createPost($createPostRequest);

        $this->cache->setCached('find-all-posts' . $post, $post->toJson());

        return response(new PostResource($post), Response::HTTP_CREATED);
    }


    public function findByPost(Post $post)
    {
        $findByPost = $this->postService->findByPost($post);

        $this->cache->getCached('findByPost');

        return response(new PostResource($findByPost), Response::HTTP_OK);
    }

    public function updatePost(UpdatePostRequest $updatePostRequest, Post $post)
    {
        $updatePost = $this->postService->updatePost($updatePostRequest, $post);

        return response(new PostResource($updatePost), Response::HTTP_OK);
    }

    public function deletePost(Post $post)
    {
        $this->postService->deletePosts($post);

        return response()->json(['data' => ['message' => '게시물이 삭제되었습니다.']], Response::HTTP_OK);
    }
}
