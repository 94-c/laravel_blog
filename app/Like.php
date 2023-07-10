<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'like'];

    /** relationShip */
    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** scope */
    public function scopeLikeableType($query, $likeableType)
    {
        return $query->where('likeable_type', $likeableType);
    }

    public function scopeLikeableId($query, $likeableId)
    {
        return $query->where('likeable_id', $likeableId);
    }

    public function scopeUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
