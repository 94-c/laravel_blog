<?php

namespace App\Observers;

use App\Comment;
use Illuminate\Support\Facades\DB;

class CommentObserver
{
    public function deleting(Comment $comment)
    {
        DB::transaction(function() use ($comment) {
           $comment->likes()->each(function($like) {
               $like->delete();
           });
        });
    }

}
