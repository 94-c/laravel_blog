<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\Eloquent\CommentRepositoryInterface;
use App\Repositories\Eloquent\UserRepositoryInterface;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, CanResetPassword;

    protected $fillable = [
        'name',
        'email',
        'password',
        'state',
        'email_verified_at',
        'provider',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** relationShip */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function providers()
    {
        return $this->hasMany(Provider::class, 'user_id', 'id');
    }

    /** 회원 가입 시 이메일 인증 전송 메소드 */
    /**
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    /** 비밀번호 분실 시 이메일 전송 */
    /**
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /** JWT Token */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function hasProfile(): bool
    {
        return $this->files()->get()->isNotEmpty();
    }

}
