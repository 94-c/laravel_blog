<?php

namespace App\Http\Controllers;

use App\Helpers\CacheHelper;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Services\LoginService;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
{
    private LoginService $loginService;
    private CacheHelper $cache;

    public function __construct(LoginService $loginService)
    {
        $this->cache = new CacheHelper('file');
        $this->loginService = $loginService;
    }


    public function register(RegisterRequest $registerRequest)
    {
        $user = $this->loginService->registerUser($registerRequest);

        $this->cache->setCached('registerUser' . $user, $user->toJson());

        return response(new RegisterResource($user), Response::HTTP_CREATED);
    }


    public function login(LoginRequest $loginRequest)
    {
        $token = $this->loginService->login($loginRequest);

        $this->cache->getCached('loginUser');

        return response(new TokenResource($token), Response::HTTP_ACCEPTED);
    }

    public function logout()
    {
        $this->loginService->logout();;

        return response(['data' => ['message' => '성공적으로 로그아웃이 되었습니다.']], Response::HTTP_OK);
    }

}
