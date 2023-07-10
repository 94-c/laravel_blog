<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Exceptions\ForbiddenCustomException;
use App\Helpers\CacheHelper;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Post;
use App\Services\CommentService;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    private CommentService $commentService;
    private CacheHelper $cache;

    public function __construct(CommentService $commentService)
    {
        $this->cache = new CacheHelper('file');
        $this->commentService = $commentService;
    }

    public function createComment(CreateCommentRequest $createCommentRequest, Post $post)
    {
        $comment = $this->commentService->createComment($createCommentRequest, $post);

        $this->cache->setCached('create-comment'.$comment, $comment->toJson());

        return response(new CommentResource($comment), Response::HTTP_CREATED);
    }

    public function findCommentsByPost(Post $post)
    {
        $comments = $this->commentService->findByComment($post);

        return response(new CommentResource($comments), Response::HTTP_OK);
    }

    public function updateComment(UpdateCommentRequest $updateCommentRequest, Post $post, Comment $comment)
    {
        $comment = $this->commentService->updateComment($updateCommentRequest, $post, $comment);

        return response(new CommentResource($comment), Response::HTTP_CREATED);
    }

    public function deleteComment(Post $post, Comment $comment)
    {
        $this->commentService->deleteComment($post, $comment);

        return response()->json(['data' => ['message' => '댓글이 삭제되었습니다.']], Response::HTTP_NO_CONTENT);

    }
}
