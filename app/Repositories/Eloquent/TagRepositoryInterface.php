<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Util\EloquentRepositoryInterface;
use App\Tag;

interface TagRepositoryInterface extends EloquentRepositoryInterface
{
    public function findAllTag();

    public function searchFindByTag(string $keyword);

    public function findPostsByTag(Tag $tag);

    public function findPostsByTagName(string $tagName);

}
