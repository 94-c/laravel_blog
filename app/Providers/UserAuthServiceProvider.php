<?php

namespace App\Providers;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Symfony\Component\HttpFoundation\Response;

class UserAuthServiceProvider implements UserProvider
{
    /*
     * 주어진 자격 증명으로 사용자를 검색합니다.
     */
    public function retrieveByCredentials(array $credentials)
    {
        return User::where('email', $credentials['email'])->where('provider', $credentials['provider'])->first();
    }

    /*
     * 사용자 비밀번호가 로그인 양식에 제공되는 비밀번호와 일치하는지 여부를 확인
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $password = md5($credentials['password']);

        return $password === $user->getAuthPassword();
    }

    /*
     * 세션 쿠키에 사용자 ID에 대한 정보가 있는 모든 요청에서 호출
     */
    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }


}
