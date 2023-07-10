<?php

namespace App\Traits;

use App\Post;
use App\Repositories\TagRepository;
use App\Tag;
use Illuminate\Foundation\Http\FormRequest;

trait ConnectionWithTag
{

    public function verifyAndAttachTag(FormRequest $request, Post $post): ?array
    {
        if (!$request->filled('tag')) {
            return null;
        }

        foreach ($request->input('tag') as $tag) {
            if (!is_null($tag)) {
                $tags[] = $this->attachTag($post, $tag);
            } else {
                $tags[] = [];
            }
        }

        return $tags;
    }

    public function attachTag(Post $post, string $tag): Tag
    {
        /** @var Tag $newTag */
        $newTag = Tag::firstOrCreate(['name' => $tag]);

        $post->tags()->attach($newTag->id);

        return $newTag;
    }

    public function detachTagsByModel(Post $post)
    {
        $post->tags()->detach();
    }
}
