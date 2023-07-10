<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Util\EloquentRepositoryInterface;
use App\User;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function findAllUsers();

    public function searchUsersKeyword(string $type, string $keyword);

    public function createUser(array $data);

    public function findByUser(int $id);

    public function updateUser(array $data);

    public function findByUserEmail(string $email);

    public function findByLoginEmail(string $email);

    public function findByUserPassword(int $userId, string $password);

    public function findPostsByUser(User $user);

    public function findCommentsByUser(User $user);

    public function findOrFailUser(int $userId);

    public function getAuthUser();

    public function findUserByProvider(string $email, string $provider);

}
