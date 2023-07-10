<?php

namespace App\Observers;

use App\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    public function deleting(Post $post)
    {
        DB::transaction(function() use ($post) {
            $post->comments()->each(function($comment) {
                $comment->likes()->delete();
                $comment->delete();
            });
        });
    }

}
