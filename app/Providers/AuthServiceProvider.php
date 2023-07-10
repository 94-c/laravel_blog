<?php

namespace App\Providers;

use App\Comment;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use App\Policies\TagPolicy;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
        Comment::class => CommentPolicy::class,
        Tag::class => TagPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // admin 권한 정의
        Gate::define('admin', function (User $user) {
            $roles = $user->roles()->select('roles.name')->get();
            foreach ($roles as $role) {
                if ($role->name === 'admin') {
                    return true;
                }
            }
            return false;
        });

        VerifyEmailNotification::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->line('이메일 검증')
                ->action('이메일 검증 확인해주세요.', $url)
                ->line('우리 프로그램을 사용해주셔서 감사합니다.');
        });

        ResetPasswordNotification::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->line('비밀번호를 잃어버리셨습니까?')
                ->action('재설정하려면 클릭하세요.', "http://laravel_blog.test/resetPassword?token={$token}&email={$notifiable->getEmailForPasswordReset()}")
                ->line('우리 프로그램을 사용해주셔서 감사합니다.');
        });

    }
}
