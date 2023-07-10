<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\VerifyRequest;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //이메일 인증
    public function verifyEmail($userId, VerifyRequest $request)
    {
        if (!$request->hasValidSignature()) {
            return response(['data' => ['message' => '유효 하지 않은 이메일입니다.']], Response::HTTP_NOT_FOUND);
        }

        /**
         * @var User $user
         */
        $user = $this->userService->findOrFailUser($userId);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            $user->update([
                'state' => 1
            ]);

        }

        return response(['data' => ['message' => '이메일 인증이 완료 되었습니다.', 'user' => $user]], Response::HTTP_OK);
    }

}
