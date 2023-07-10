<?php

namespace App\Repositories;

use App\Post;
use App\Repositories\Eloquent\PostRepositoryInterface;
use App\Repositories\Util\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function findAllPost()
    {
        return $this->getQueryBuilder()
            ->where('active', 1)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
    }

    public function searchFindByPost(string $type, string $keyword): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->getQueryBuilder()
            ->select('posts.*', 'users.name')
            ->join('users', 'users.id', '=', 'user_id')
            ->where($type, 'like', '%' . $keyword . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
    }


    public function createPost(array $data): Model
    {
        return $this->create($data);
    }

    public function findByPost(int $id)
    {
        return $this->model
            ->with('comments', 'tags', 'files', 'images', 'likes')
            ->where('id', $id)
            ->get();
    }

    public function updatePost(array $data): bool
    {
        return $this->update($data);
    }

    public function updateStatePost(array $data): bool
    {
        return $this->update($data);
    }


}
