<?php

namespace App\Http\Controllers;


use App\Exceptions\ForbiddenCustomException;
use App\Http\Requests\User\ActiveUserRequest;
use App\Http\Requests\User\CreateProfileImageRequest;
use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\EmailRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\UploadFile;
use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function findByUser()
    {
        $authUser = $this->userService->findByAuthUser();

        return response(new UserResource($authUser), Response::HTTP_OK);
    }

    /**
     * @param UpdateUserRequest $updateUserRequest
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateUser(UpdateUserRequest $updateUserRequest, User $user)
    {
        $updateUser = $this->userService->updateUser($updateUserRequest, $user);

        return response(new UserResource($updateUser), Response::HTTP_OK);
    }

    /**
     * @param EmailRequest $emailRequest
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function forgotPassword(EmailRequest $emailRequest)
    {
        $status = $this->userService->forgotPassword($emailRequest);

        switch ($status) {
            case Password::RESET_LINK_SENT :
                return response(['data' => ['message' => '비밀번호 변경 메일이 성공적으로 발송 되었습니다.']], Response::HTTP_OK);
            case Password::RESET_THROTTLED :
                return redirect()->back()->withErrors(['sendError' => '이미 발송되었습니다. 이메일을 확인해주세요.']);
            case Password::INVALID_USER :
                return redirect()->back()->withErrors(['sendError' => '존재하지 않거나 비활성화 된 계정입니다. 관리자에게 문의해주세요.']);
            default :
                return redirect()->back()->withErrors(['sendError' => '이메일 발송 실패했습니다. 재시도 해주세요.']);
        }
    }

    /**
     * @param ResetPasswordRequest $passwordRequest
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resetPassword(ResetPasswordRequest $passwordRequest)
    {
        switch ($this->userService->resetPassword($passwordRequest)) {
            case Password::PASSWORD_RESET :
                return response(['message' => '비밀번호가 변경되었습니다.'], Response::HTTP_CREATED);
            case Password::RESET_THROTTLED :
                return response(['resetError' => '이미 발송되었습니다. 이메일을 확인해주세요.'], Response::HTTP_ACCEPTED);
            case Password::INVALID_TOKEN :
                return response(['resetError' => '만료된 토큰입니다.'], Response::HTTP_FORBIDDEN);
            default :
                return response(['resetError' => '변경 실패했습니다. 재시도 해주세요.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CreateProfileImageRequest $createProfileImageRequest
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createOrUpdateProfileImage(CreateProfileImageRequest $createProfileImageRequest)
    {
        $image = $this->userService->createOrUpdateProfileImage($createProfileImageRequest);

        return response(new UserResource(['user' => Auth::user(), 'image' => $image]), Response::HTTP_CREATED);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProfileImage(): \Illuminate\Http\JsonResponse
    {
        $this->userService->deleteProfileImage();

        return response()->json(['data' => ['message' => '프로필 사진이 삭제되었습니다.']], Response::HTTP_OK);
    }

    /**
     * 회원 활성화
     * @param ActiveUserRequest $activeUserRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function enableUser(ActiveUserRequest $activeUserRequest): \Illuminate\Http\JsonResponse
    {
        $this->userService->updateStateUser($activeUserRequest, 1);

        return response()->json(['message' => '활성화 되었습니다.'], Response::HTTP_OK);
    }


    /**
     * 회원 비활성화
     * @param ActiveUserRequest $activeUserRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function disableUser(ActiveUserRequest $activeUserRequest): \Illuminate\Http\JsonResponse
    {
        $this->userService->updateStateUser($activeUserRequest, 0);

        return response()->json(['message' => '비활성화 되었습니다.'], Response::HTTP_OK);
    }

    /**
     * 회원 영구 탈퇴
     * @param DestroyUserRequest $destroyUserRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyUserRequest $destroyUserRequest): \Illuminate\Http\JsonResponse
    {
        $this->userService->destroyUser($destroyUserRequest);

        return response()->json(['message' => '탈퇴 완료되었습니다.'], Response::HTTP_OK);
    }
}
