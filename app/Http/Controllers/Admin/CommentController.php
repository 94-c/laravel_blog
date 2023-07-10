<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Exceptions\NotFoundCustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\FindCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @param FindCommentRequest $findCommentRequest
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws NotFoundCustomException
     */
    public function findAllComments(FindCommentRequest $findCommentRequest)
    {
        $comments = $this->commentService->findAllComments($findCommentRequest);

        return response(new CommentResource($comments), Response::HTTP_OK);
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function findByComment(Comment $comment)
    {
        $image = $this->commentService->findByAdminComment($comment);

        return response(new CommentResource([$comment, ['image' => $image]]), Response::HTTP_OK);
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function disableComment(Comment $comment): \Illuminate\Http\JsonResponse
    {
        $this->commentService->updateStateComment($comment,0);

        return response()->json(['data' => ['message' => '댓글이 비활성화가 되었습니다.']], Response::HTTP_OK);
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function enableComment(Comment $comment): \Illuminate\Http\JsonResponse
    {
        $this->commentService->updateStateComment($comment,1);

        return response()->json(['data' => ['message' => '댓글이 활성화가 되었습니다.']], Response::HTTP_OK);
    }
}
