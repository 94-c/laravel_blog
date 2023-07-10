<?php

namespace App\Services;

use App\Exceptions\NotFoundCustomException;
use App\Repositories\Eloquent\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Traits\UploadFile;
use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    use UploadFile;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param FormRequest $data
     * @return LengthAwarePaginator
     */
    public function findAllUser(FormRequest $data): LengthAwarePaginator
    {
        $type = $data->query('type', 'name');
        $keyword = $data->query('keyword');

        /** @var LengthAwarePaginator $users */
        $users = is_null($keyword)
            ? $this->userRepository->findAllUsers()
            : $this->userRepository->searchUsersKeyword($type, $keyword);

        if ($users->isEmpty()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $users;
    }

    public function createUser(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->userRepository->create($data);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function findByAuthUser(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard('api')->user();
    }

    /**
     * @param FormRequest $data
     * @param User $user
     * @return bool
     */
    public function updateUser(FormRequest $data, User $user): bool
    {
        $update = ([
            'name' => $data->input('name')
        ]);

        return $this->userRepository->updateUser($update);
    }


    /**
     * 비밀번호 초기화
     * @param FormRequest $data
     * @return string
     */
    public function forgotPassword(FormRequest $data): string
    {
        /** @var User|null $email */
        $email = $this->userRepository->findByUserEmail($data->input('email'));

        //비밀번호 초기화
        $email->update([
            'password' => md5(Str::random(8)),
            'state' => 2
        ]);

        //메일 링크 생성 및 메일 전송
        return Password::sendResetLink(
            $data->only('email', 'provider')
        );
    }

    /**
     * 비밀번호 변경 메일 확인 후 비밀번호 변경
     * @param FormRequest $data
     * @return mixed
     */
    public function resetPassword(FormRequest $data)
    {
        return Password::reset(
            $data->only('email', 'password', 'password_confirm', 'token', 'provider'),
            function ($user) use ($data) {
                $user->update([
                    'password' => md5($data->input('password')),
                    'state' => 1,
                ]);

                event(new PasswordReset($user));

                Auth::guard('api')->login($user);
            }
        );
    }

    /**
     * 로그인 된 유저 정보
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private function getAuthUser(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return $this->userRepository->getAuthUser();
    }

    /**
     * 유저 이미지 생성 및 변경
     * @param FormRequest $data
     * @return array|null
     */
    public function createOrUpdateProfileImage(FormRequest $data): ?array
    {
        if ($this->getAuthUser()->hasProfile()) {
            $this->deleteFileByModel($this->getAuthUser());
        }

        return $this->verifyAndUploadFile($data, $this->getAuthUser(), 'image');
    }

    /**
     * 유저 이미지 삭제
     * @return void
     */
    public function deleteProfileImage()
    {
        $this->deleteFileByModel($this->getAuthUser());

    }

    public function destroyUser(FormRequest $data)
    {
        $password = $data->input('password');

        if (Auth::guard('api')->user()->getAuthPassword() != md5($password)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        Auth::guard('api')->user()->update([
            'name' => '알수없음',
            'email' => null,
            'password' => '',
            'state' => 0,
        ]);

    }

    public function updateStateUser(FormRequest $data, int $state): bool
    {
        $userId = Auth::guard('api')->id();

        $findByPassword = $this->userRepository->findByUserPassword($userId, $data->input('password'));

        if (is_null($findByPassword)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $this->userRepository->updateUser([
            'state' => $state
        ]);
    }

    public function socialFindByUser(User $user, string $provider)
    {
        return $user->find($user)->where('provider', $provider)->first();
    }

    public function findOrFailUser(int $userId)
    {
        return $this->userRepository->findOrFailUser($userId);
    }

    public function findPostsByUser(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $posts = $this->userRepository->findPostsByUser($user);

        if ($posts->isEmpty()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $posts;
    }

    public function findCommentsByUser(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $comments = $this->userRepository->findCommentsByUser($user);

        if ($comments->isEmpty()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $comments;
    }

    public function findByUser(User $user): \Illuminate\Database\Eloquent\Model
    {
        return $this->userRepository->findByUser($user->id);
    }

    public function updateUserRole(FormRequest $data, User $user)
    {
        $user->roles()->attach($data->input('roleId'));
    }

    public function findUserByProvider(string $email, string $provider)
    {
        return $this->userRepository->findUserByProvider($email, $provider);
    }
}

