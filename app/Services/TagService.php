<?php

namespace App\Services;

use App\Post;
use App\Repositories\Eloquent\TagRepositoryInterface;
use App\Repositories\TagRepository;
use App\Tag;
use App\Traits\ConnectionWithTag;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class TagService
{
    use ConnectionWithTag;

    private TagRepositoryInterface $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function findAllTag(FormRequest $data)
    {
        $keyword = $data->query('keyword');

        $tags = is_null($keyword)
            ? $this->tagRepository->findAllTag()
            : $this->tagRepository->searchFindByTag($keyword);

        if ($tags->isEmpty()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $tags;
    }

    public function findPostsByTag(Tag $tag): \Illuminate\Database\Eloquent\Collection
    {
        return $this->tagRepository->findPostsByTag($tag);
    }

    public function findPostsByTagName(string $tagName)
    {
        return $this->tagRepository->findPostsByTagName($tagName);
    }

    public function deleteTagFromPost(Post $post, Tag $tag)
    {
        $tag->posts()->detach($post->id);
    }


}
