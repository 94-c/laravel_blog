<?php

namespace App\Repositories;

use App\Helpers\CacheHelper;
use App\Repositories\Eloquent\UserRepositoryInterface;
use App\Repositories\Util\BaseRepository;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findAllUsers()
    {
        return $this->getQueryBuilder()
            ->where('state', 1)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
    }

    public function searchUsersKeyword(string $type, string $keyword)
    {
        return $this->getQueryBuilder()
            ->where($type, 'like', '%' . $keyword . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
    }

    public function createUser(array $data)
    {
        return $this->create($data);
    }

    public function findByUser(int $id)
    {
        return $this->findById($id);
    }

    public function updateUser(array $data): bool
    {
        return $this->update($data);
    }

    public function findByUserEmail(string $email)
    {
        return $this->getQueryBuilder()
            ->where('email', $email)
            ->first();
    }

    public function findByLoginEmail(string $email)
    {
        return $this->getQueryBuilder()
            ->where('email', $email)
            ->where('provider', 'mailTrap')
            ->where('state', 1)
            ->first();
    }

    public function findByUserPassword(int $userId, string $password)
    {
        return $this->getQueryBuilder()
            ->where('id', $userId)
            ->where('password', md5($password))
            ->first();
    }

    public function findPostsByUser(User $user)
    {
        return $user->posts()->get();
    }

    public function findCommentsByUser(User $user)
    {
        return $user->comments()->get();
    }

    public function findOrFailUser(int $userId)
    {
        return $this->model->findOrFail($userId);
    }

    public function getAuthUser(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard('api')->user();
    }

    public function findUserByProvider(string $email, string $provider)
    {
        return $this->getQueryBuilder()
            ->where('email', $email)
            ->where('provider', $provider)
            ->first();
    }

}
