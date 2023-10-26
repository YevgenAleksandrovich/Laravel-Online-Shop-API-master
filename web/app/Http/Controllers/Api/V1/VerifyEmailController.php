<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends ApiController
{
    /**
     * Constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(protected AuthService $authService) {}

    /**
     * Email verification.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function validateEmail(Request $request): JsonResponse
    {
        $user = User::find($request->route('id'));

        if ($this->authService->userEmailVerify($user)) {
            return $this->sendSuccess();
        }

        return $this->sendError();
    }

    /**
     * Re-send Email verification message.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();
        return $this->sendSuccess();
    }
}
