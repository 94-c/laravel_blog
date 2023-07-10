<?php

namespace App\Repositories\Eloquent;

use App\Comment;
use App\Repositories\Util\EloquentRepositoryInterface;

interface CommentRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByComment(Comment $comment);

    public function updateStateComment(array $data);

    public function findAllComment();

    public function searchFindByComment(string $type, string $keyword);
}
