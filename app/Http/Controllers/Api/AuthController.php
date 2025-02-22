<?php

namespace app\Http\Controllers\Api;

use Illuminate\Http\Request;
use app\Enums\HttpStatusCode;
use app\Http\Services\Api\AuthService;
use app\Http\Requests\Api\LoginRequest;
use app\Http\Requests\Api\RegisterRequest;
use app\Http\Controllers\Api\BaseApiController;
use Modules\Auth\app\Http\Requests\ForgetPasswordRequest;

class AuthController extends BaseApiController
{

    public function __construct(protected AuthService $authService)
    {
        parent::__construct();
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request->validated());

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->validated());

        if(! $response['success']) {
            return sendApiFailResponse($response['message'], code: $response['code'] ?? HttpStatusCode::BAD_REQUEST);
        }

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $response = $this->authService->forgetPassword($request->validated());

        if(! $response['success']) {
            return sendApiFailResponse($response['message'], errors: $response['errors']);
        }

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $response = $this->authService->logout($user);

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function verifyEmail($token)
    {
        $response = $this->authService->verifyEmail($token);

        if(! $response['success']) {
            return redirect('/email-verification-failed');
        }

        return redirect('/email-verification-success');
    }

    public function resendVerificationEmail(Request $request)
    {
        $response = $this->authService->resendVerificationEmail($request->email);

        if(! $response['success']) {
            return sendApiFailResponse($response['message']);
        }

        return sendApiSuccessResponse($response['message'], $response['data']);
    }
}
