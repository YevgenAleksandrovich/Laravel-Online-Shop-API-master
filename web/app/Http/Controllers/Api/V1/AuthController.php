<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\LoginUserRequest;
use Illuminate\Http\Request;
use App\Http\Requests\V1\StoreUserRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    public function __construct(protected AuthService $authService) {}

    /**
     * User register.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        $user = $this->authService->userRegister($request->validated());

        return $this->sendSuccess('New user created', [
            'user' => $user,
            'token' => $this->authService->userTokenCreate($user),
        ]);
    }

    /**
     * User login.
     *
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        if($user = $this->authService->userLogin($request->only('email', 'password'))) {
            return $this->sendSuccess('User login successfully.', [
                'user' => $user,
                'token' => $this->authService->userTokenCreate($user),
            ]);
        }

        return $this->sendError('Credentioal do not match.', [], 401);
    }

    /**
     * User logout.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        if ($this->authService->userLogout()) {
            return $this->sendSuccess('Logged out successfully.');
        }

        return $this->sendError('Log out error.');
    }

    /**
     * User destroy.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        if ($this->authService->userDelete()) {
            return $this->sendSuccess();
        }

        return $this->sendError();
    }
}
