<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotFoundCustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ActiveUserRequest;
use App\Http\Requests\User\FindUserRequest;
use App\Http\Requests\User\UpdateUserRoleRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function findAllUsers(FindUserRequest $findUserRequest)
    {
        $users = $this->userService->findAllUser($findUserRequest);

        return response(new UserResource($users), Response::HTTP_OK);
    }

    public function findByUser(User $user)
    {
        $image = $this->userService->findByUser($user);

        return response(new UserResource($user), Response::HTTP_OK);
    }

    public function findPostsByUser(User $user)
    {
        $posts = $this->userService->findPostsByUser($user);

        return response(new PostResource($posts), Response::HTTP_OK);
    }

    public function findCommentsByUser(User $user)
    {
        $posts = $this->userService->findCommentsByUser($user);

        return response(new PostResource($posts), Response::HTTP_OK);
    }

    public function enableUser(ActiveUserRequest $activeUserRequest, User $user)
    {
        $this->userService->updateStateUser($activeUserRequest, 1);

        return response()->json(['data' => ['message' => '활성화 되었습니다.']], Response::HTTP_OK);
    }

    public function disableUser(ActiveUserRequest $activeUserRequest, User $user)
    {
        $this->userService->updateStateUser($activeUserRequest, 0);

        return response()->json(['data' => ['message' => '비활성화 되었습니다.']], Response::HTTP_OK);
    }

    public function updateUserRole(UpdateUserRoleRequest $updateUserRoleRequest, User $user)
    {
        $this->userService->updateUserRole($updateUserRoleRequest, $user);

        return response()->json(['data' => ['message' => '권한이 업데이트 되었습니다.']], Response::HTTP_OK);
    }
}

