<?php

namespace App\Providers;

use App\Repositories\CommentRepository;
use App\Repositories\Eloquent\CommentRepositoryInterface;
use App\Repositories\Eloquent\PostRepositoryInterface;
use App\Repositories\Eloquent\RoleRepositoryInterface;
use App\Repositories\Eloquent\TagRepositoryInterface;
use App\Repositories\Eloquent\UserRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

    }
}
