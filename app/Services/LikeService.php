<?php

namespace App\Services;

use App\Comment;
use App\Post;
use App\Traits\CreateLike;
use Illuminate\Foundation\Http\FormRequest;

class LikeService
{
    use CreateLike;

    public function updateLike(FormRequest $data, Post $post, ?Comment $comment)
    {
        return $data->route()->named('post.like')
            ? $post
            : $comment;
    }

    public function findLikesByModel(Post $post, ?Comment $comment): \Illuminate\Database\Eloquent\Collection
    {
        $model = request()->route()->named('post.likes')
            ? $post
            : $comment;

        return $model->likes()->get();
    }
}
