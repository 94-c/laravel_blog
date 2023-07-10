<?php

namespace App;

use App\Repositories\Eloquent\CommentRepositoryInterface;
use App\Repositories\Eloquent\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];

    /** relationShip */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags', 'tag_id');
    }

    /** method */
    public function scopeFindByName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function scopeName($query, $tagName)
    {
        return $query->where('name', 'like', '%' . $tagName . '%');
    }

    public function scopeFindPostsByTagName($query, $name)
    {
        return $query->select('posts.*')
            ->join('post_tags', 'post_tags.tag_id', '=', 'tags.id')
            ->join('posts', 'posts.id', '=', 'post_tags.post_id')
            ->where('tags.name', 'like', '%' . $name . '%');
    }

    /** Repository */
    public function repository()
    {
        return app(TagRepositoryInterface::class)->setModel($this);
    }

}
