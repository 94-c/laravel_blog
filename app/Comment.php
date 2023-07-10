<?php

namespace App;

use App\Repositories\CommentRepository;
use App\Repositories\Eloquent\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'body', 'parent_id', 'user_ip', 'active'];

    /** relationShip */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function subComments()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id')->with('likes');
    }

    public function activeSubComments()
    {
        return $this->subComments()->active();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /** scope */
    public function scopeSearchKeyword($query, $type, $keyword)
    {
        return $query
            ->select('comments.*', 'users.name')
            ->join('users', 'users.id', '=', 'user_id')
            ->where($type, 'like', '%' . $keyword . '%');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeParent($query)
    {
        return $query->where('parent_id', 0);
    }

    public function scopeParentId($query, $id)
    {
        return $query->where('parent_id', $id);
    }

    public function scopeActiveSubComments($query)
    {
        return $query->subComments()->active();
    }

    /** check state */
    public function isActive(): bool
    {
        return $this->active == 1;
    }

    /** Repository */
    public function repository()
    {
        return app(CommentRepositoryInterface::class)->setModel($this);
    }

}
