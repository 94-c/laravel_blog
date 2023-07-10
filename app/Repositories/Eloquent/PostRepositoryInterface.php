<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Util\EloquentRepositoryInterface;

interface PostRepositoryInterface extends EloquentRepositoryInterface
{
    public function findAllPost();

    public function searchFindByPost(string $type, string $keyword);

    public function createPost(array $data);

    public function findByPost(int $id);

    public function updateStatePost(array $data);

    public function updatePost(array $data);

}
