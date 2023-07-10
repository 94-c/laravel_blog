<?php

namespace App;

use App\Repositories\Eloquent\CommentRepositoryInterface;
use App\Repositories\Eloquent\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'active'];

    protected $hidden = ['active'];

    /** relationShip */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable')->where('type', 'file');
    }

    public function images()
    {
        return $this->morphMany(File::class, 'fileable')->where('type', 'image');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /** Repository */
    public function repository()
    {
        return app(PostRepositoryInterface::class)->setModel($this);
    }

    public function isActive(): bool
    {
        return $this->active == 1;
    }
}
