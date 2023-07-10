<?php

namespace App\Repositories;

use App\Comment;
use App\Repositories\Eloquent\CommentRepositoryInterface;
use App\Repositories\Util\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{

    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    public function findAllComment()
    {
        return $this->getQueryBuilder()
            ->where('active', 1)
            ->orderByDesc('created_at')
            ->paginate(5);
    }

    public function searchFindByComment(string $type, string $keyword)
    {
        return $this->getQueryBuilder()
            ->select('comments.*')
            ->where($type, 'like', '%' . $keyword . '%')
            ->orderByDesc('created_at')
            ->paginate(5);
    }

    public function findByComment(Comment $comment)
    {
        return $comment->files()
            ->where('type', 'image')
            ->get();
    }

    public function updateStateComment(array $data)
    {
        return $this->model->update($data);
    }
}
