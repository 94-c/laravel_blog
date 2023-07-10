<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\Like\LikeCreateRequest;
use App\Http\Resources\LikeResource;
use App\Post;
use App\Services\LikeService;
use App\Traits\createLike;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{

    private LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function updateLike(LikeCreateRequest $likeCreateRequest, Post $post, ?Comment $comment)
    {
        $model = $this->likeService->updateLike($likeCreateRequest, $post, $comment);

        return $this->likeService->verifyLikeAndUpdateLike($model);
    }

    public function findLikesByModel(Post $post, ?Comment $comment)
    {
        $like = $this->likeService->findLikesByModel($post, $comment);

        return response(new LikeResource($like), Response::HTTP_OK);
    }
}
