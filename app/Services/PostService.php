<?php

namespace App\Services;

use App\Helpers\CacheHelper;
use App\Post;
use App\Repositories\Eloquent\PostRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\Util\BaseRepository;
use App\Repositories\UserRepository;
use App\Traits\ConnectionWithTag;
use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PostService
{
    private PostRepositoryInterface $postRepository;

    use UploadFile, ConnectionWithTag;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param FormRequest $data
     * @return LengthAwarePaginator
     */
    public function findAllPosts(FormRequest $data): LengthAwarePaginator
    {
        $type = $data->query('type', 'title');
        $keyword = $data->query('keyword');

        /** @var LengthAwarePaginator $posts */
        $posts = is_null($keyword)
            ? $this->postRepository->findAllPost()
            : $this->postRepository->searchFindByPost($type, $keyword);

        if ($posts->isEmpty()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $posts;
    }

    /**
     * @param FormRequest $data
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function createPost(FormRequest $data)
    {
        /** @var Post $post */
        $post = [
            'title' => $data->input('title'),
            'body' => $data->input('body'),
            'user_id' => Auth::id(),
        ];

        /** @var Post|null $createdPost */
        $createdPost = $this->postRepository->createPost((array)$post);

        $this->verifyAndAttachTag($data, $createdPost);
        $this->verifyAndUploadFile($data, $createdPost, 'image');
        $this->verifyAndUploadFile($data, $createdPost, 'file');

        return $createdPost->with('tags', 'images', 'files', 'likes')->where('id', $createdPost->id)->get();
    }

    /**
     * @param Post $post
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function findByPost(Post $post)
    {
        if (!$post->isActive()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $this->postRepository->findByPost($post->id);
    }

    /**
     * @param FormRequest $data
     * @param Post $post
     * @return Post
     */
    public function updatePost(FormRequest $data, Post $post): Post
    {
        $this->detachTagsByModel($post);
        $this->verifyAndAttachTag($data, $post);

        $this->deleteFileByModel($post);
        $this->verifyAndUploadFile($data, $post, 'image');
        $this->verifyAndUploadFile($data, $post, 'file');

        $post->update([
            'title' => $data->input('title'),
            'body' => $data->input('body'),
        ]);

        $this->detachTagsByModel($post);
        $this->verifyAndAttachTag($data, $post);

        return $post;
    }

    /**
     * @param Post $post
     * @return void
     */
    public function deletePosts(Post $post)
    {
        if (!Gate::allows('delete', $post)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        DB::transaction(function () use ($post) {
            $this->deleteFileByModel($post);
            $this->detachTagsByModel($post);
            $post->delete();
        });
    }


    public function updateStatePost(Post $post, int $state): bool
    {
        return $this->postRepository->updateStatePost([
            'active' => $state
        ]);
    }
}
