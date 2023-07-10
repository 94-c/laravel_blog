<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\FindTagRequest;
use App\Http\Resources\TagResource;
use App\Post;
use App\Services\TagService;
use App\Tag;
use App\Traits\ConnectionWithTag;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    private TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function findPostsByTag(Tag $tag)
    {
        $posts = $this->tagService->findPostsByTag($tag);

        return response(new TagResource([$tag, ['posts' => $posts]]), Response::HTTP_OK);
    }

    public function findPostsByTagName(FindTagRequest $findTagRequest)
    {
        /** @var string $tagName */
        $tagName = $findTagRequest->query('tagName');

        //TODO findPostsByTagName을 어떻게 할 것인가? Repository에 만들 것인가?
        $posts = Tag::findPostsByTagName($tagName)->get();

        return response(new TagResource([$tagName, ['posts' => $posts]]), Response::HTTP_OK);
    }

    public function deleteTagFromPost(Post $post, Tag $tag)
    {
        $this->tagService->deleteTagFromPost($post, $tag);

        return response()->json(['data' => ['message' => '태그가 삭제되었습니다.']], Response::HTTP_OK);
    }

    public function deleteAllTagsFromPost(Post $post)
    {
        $this->tagService->detachTagsByModel($post);

        return response()->json(['data' => ['message' => '모든 태그가 삭제되었습니다.']], Response::HTTP_OK);
    }
}
