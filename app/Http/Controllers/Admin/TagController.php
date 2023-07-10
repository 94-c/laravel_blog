<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotFoundCustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\FindTagRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Post;
use App\Services\TagService;
use App\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    private TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function findAllTags(FindTagRequest $findTagRequest)
    {
        $tags = $this->tagService->findAllTag($findTagRequest);

        return response(new TagResource($tags), Response::HTTP_OK);
    }

    public function findPostsByTag(Tag $tag)
    {
        $posts = $this->tagService->findPostsByTag($tag);

        return response(new TagResource([$tag, ['posts' => $posts]]), Response::HTTP_OK);
    }

    public function findPostsByTagName(FindTagRequest $findTagRequest)
    {
        $tagName = $findTagRequest->query('tagName');

        $posts = Tag::findPostsByTagName($tagName)->get();

        return response(new TagResource([$tagName, ['posts' => $posts]]), Response::HTTP_OK);
    }
}
