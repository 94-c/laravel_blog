<?php

namespace App\Repositories;

use App\Repositories\Eloquent\TagRepositoryInterface;
use App\Repositories\Util\BaseRepository;
use App\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }

    public function findAllTag()
    {
        return $this->model->orderBy('created_at', 'DESC')->paginate(5);
    }

    public function searchFindByTag(string $keyword)
    {
        return $this->model->searchKeyword($keyword)->orderBy('created_at', 'DESC')->paginate(5);
    }

    public function findPostsByTag(Tag $tag)
    {
        return $tag->posts()->get();
    }

    public function findPostsByTagName(string $tagName)
    {
        return $this->model->findPostsByTagName($tagName);
    }

}
