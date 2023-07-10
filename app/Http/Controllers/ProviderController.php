<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundCustomException;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isFalse;

class ProviderController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $social = Socialite::driver($provider)->stateless()->user();

        $users = $this->userService->findUserByProvider($social->getEmail(), $provider);

        if ($users) {
            return $this->socialitLogin($users, $provider);
        }

        $createSocialUser = $this->userService->createUser(
            [
                'email' => $social->getEmail(),
                'email_verified_at' => now(),
                'name' => $social->getName(),
                'state' => 1,
                'provider' => $provider,
                'password' => md5($social->getEmail()),
            ]
        );

        $createSocialUser->providers()->updateOrCreate(
            ['provider' => $provider, 'provider_id' => $social->getId()],
            ['avatar' => $social->getAvatar()]
        );
        return $this->socialitLogin($createSocialUser, $provider);
    }

    public function socialitLogin($users, $provider)
    {
        $user = $this->userService->socialFindByUser($users, $provider);

        if ($user->provider == $provider) {
            $token = Auth::guard('api')->attempt([
                'email' => $user->email,
                'password' => $user->email,
                'provider' => $provider,
            ]);
            return response(new TokenResource($token), Response::HTTP_ACCEPTED);
        }

        return response(['message' => 'error'], Response::HTTP_ACCEPTED);

    }


}
