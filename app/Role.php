<?php

namespace App;

use App\Repositories\Eloquent\CommentRepositoryInterface;
use App\Repositories\Eloquent\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Role extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    /** relationship */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    /** scope */
    public function scopeFindIdByName($query, $roleName)
    {
        $role = $query->select('id')->where('name', $roleName)->first();
        Log::debug('role = '.$role);
        return $role;
    }

    /** Repository */
    public function repository()
    {
        return app(RoleRepositoryInterface::class)->setModel($this);
    }
}
