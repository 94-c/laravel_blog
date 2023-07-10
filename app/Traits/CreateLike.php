<?php

namespace App\Traits;

use App\Comment;
use App\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

trait CreateLike
{
    public function verifyLikeAndUpdateLike(Model $model)
    {
        /** @var Collection $like
         * @var Post|Comment $model
         */
        $like = $model->likes()->userId(Auth::id())->get();

        if ($like->isEmpty()) {
            $this->createLike($model);
            return response()->json(['data' => ['message' => '좋아요가 등록되었습니다.']], Response::HTTP_CREATED);
        } else {
            $this->deleteLike($model);
            return response()->json(['data' => ['message' => '좋아요가 삭제되었습니다.']], Response::HTTP_OK);
        }
    }

    public function createLike(Model $model)
    {
        /** @var Post|Comment $model */
        return $model->likes()->create([
            'user_id' => Auth::id(),
            'like' => 1,
            'likeable_type' => $model->getMorphClass(),
            'likeable_id' => $model->id,
        ]);
    }

    public function deleteLike(Model $model)
    {
        /** @var Post|Comment $model */
        return $model->likes()->where('user_id', Auth::id())->delete();
    }
}
