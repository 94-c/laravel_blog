<?php

namespace App\Services;

use App\Comment;
use App\Exceptions\NotFoundCustomException;
use App\Post;
use App\Repositories\CommentRepository;
use App\Repositories\Eloquent\CommentRepositoryInterface;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CommentService
{
    private CommentRepositoryInterface $commentRepository;

    use UploadFile;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(FormRequest $data, Post $post)
    {
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $data->input('body'),
            'parent_id' => $data->input('parentId'),
            'user_ip' => $data->getClientIp(),
        ]);

        $this->verifyAndUploadFile($data, $comment, 'image');

        return $comment->with('files')->where('id', $comment->id)->get();
    }

    /**
     * @param Post $post
     * @return Collection
     */
    public function findByComment(Post $post): Collection
    {
        /** @var Collection $comments */
        $comments = $post->comments()
            ->with('activeSubComments', 'likes')
            ->parent()
            ->active()
            ->get();

        return $comments;

    }

    /**
     * @param FormRequest $data
     * @param Post $post
     * @param Comment $comment
     * @return array
     */
    public function updateComment(FormRequest $data, Post $post, Comment $comment): array
    {
        if (!Gate::allows('update', $comment)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $comment->update([
            'body' => $data->input('body'),
            'user_ip' => $data->getClientIp(),
        ]);

        $this->deleteFileByModel($comment);
        $image = $this->verifyAndUploadFile($data, $comment, 'image');

        return [$comment, ['image' => $image]];
    }

    /**
     * @param Post $post
     * @param Comment $comment
     * @return void
     */
    public function deleteComment(Post $post, Comment $comment)
    {
        if (!Gate::allows('delete', $comment)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        DB::transaction(function () use ($comment) {
            $subComments = Comment::parentId($comment->id)->get();

            foreach ($subComments as $subComment) {
                $this->deleteFileByModel($subComment);
                $subComment->delete();
            }

            $this->deleteFileByModel($comment);
            $comment->delete();
        });

    }

    /**
     * @throws NotFoundCustomException
     */
    public function findAllComments(FormRequest $data): LengthAwarePaginator
    {
        $type = $data->query('type', 'body');
        $keyword = $data->query('keyword');

        /** @var LengthAwarePaginator $comments */
        $comments = is_null($keyword)
            ? $this->commentRepository->findAllComment()
            : $this->commentRepository->searchFindByComment($type, $keyword);

        if ($comments->isEmpty()) {
            throw new NotFoundCustomException();
        }

        return $comments;
    }

    /**
     * @param Comment $comment
     * @return Collection
     */
    public function findByAdminComment(Comment $comment): Collection
    {
        return $this->commentRepository->findByComment($comment);
    }

    /**
     * @param Comment $comment
     * @param int $state
     * @return bool
     */
    public function updateStateComment(Comment $comment, int $state): bool
    {
        return $this->commentRepository->updateStateComment([
            'active' => $state
        ]);
    }


}
