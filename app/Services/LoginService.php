<?php

namespace App\Services;

use App\Helpers\CacheHelper;
use App\Http\Resources\UserResource;
use App\Repositories\Eloquent\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param FormRequest $data
     * @return mixed
     */
    public function registerUser(FormRequest $data)
    {
        $findByEmail = $this->userRepository->findByUserEmail($data->input('email'));

        if ($findByEmail instanceof User) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $registerUser = $this->userRepository->createUser([
            'email' => $data->input('email'),
            'password' => md5($data->input('password')),
            'name' => $data->input('name')
        ]);

        $this->sendVerificationEmail($registerUser);

        return $registerUser;
    }

    /**
     * @param User $user
     * @return \string[][]
     */
    public function sendVerificationEmail(User $user): array
    {
        if ($user->hasVerifiedEmail()) {
            return ['data' => ['message' => '이미 인증 된 이메일 입니다.']];
        }

        $user->sendEmailVerificationNotification();

        return ['data' => ['message' => '이메일 인증이 전송 되었습니다.']];
    }

    /**
     * @param FormRequest $data
     * @return bool
     */
    public function login(FormRequest $data)
    {
        $user = $this->userRepository->findByLoginEmail($data->input('email'));

        if (is_null($user)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $token = Auth::guard('api')->attempt([
            'email' => $data->input('email'),
            'password' => $data->input('password'),
            'provider' => 'mailTrap',
        ]);

        return $token;
    }

    /**
     * @return void
     */
    public function logout()
    {
        Auth::guard('api')->logout();
    }
}
