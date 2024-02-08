<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ConfirmRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResetConfirmRequest;
use App\Http\Requests\Api\ResetRequest;
use App\Http\Requests\Api\SetPasswordRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\Api\MeResource;
use App\Services\Auth\AuthService;
use App\Services\Sms\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;


/**
 * @tags Authorization
 */
class AuthController extends Controller
{
    use BaseController;

    public AuthService $service;

    public function __construct()
    {
        $this->middleware("auth:sanctum")->only(["me", "setPassword"]);
        $this->service = new AuthService();
    }

    /**
     * Register new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     * @response array{success:true,message:string}
     */
    function register(RegisterRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        try {
            SmsService::sendConfirm($phone);
            return $this->success(__("sms.send:success"));
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Confirm otp
     *
     * @param ConfirmRequest $request
     * @return JsonResponse
     * @response array{success:true,data:array{token:string}}
     */
    function confirm(ConfirmRequest $request): JsonResponse
    {
        $code = $request->input("code");
        $phone = $request->input("phone");
        try {
            return $this->service->confirm($phone, $code);
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @response array{success:true,data:array{token:string}}
     */
    function login(LoginRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        $password = $request->input("password");

        try {
            $token = $this->service->login($phone, $password);
            return $this->success(data: [
                "token" => $token
            ]);
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }

    }

    /**
     * Get user data
     *
     * @return JsonResponse
     *
     * @response MeResource
     */
    function me(): JsonResponse
    {
        return $this->success(data: MeResource::make(Auth::user()));
    }

    /**
     * Update user data
     *
     * @param UserUpdateRequest $request
     * @return JsonResponse
     * @response array{success:true,message:string}
     */
    function update(UserUpdateRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $user->fill($request->validated());
            $user->save();
            return $this->success(__("user.update:profile"));
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Reset password
     *
     * Reset user password
     *
     * @param ResetRequest $request
     * @return JsonResponse
     *
     * @response array{success:true,message:string}
     */
    function reset(ResetRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        try {
            SmsService::sendConfirm($phone);
            return $this->success(__("sms.send:success"));
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Reset otp confirm
     *
     * @param ResetConfirmRequest $request
     * @return JsonResponse
     *
     * @response array{success:true,message:string}
     */
    function resetConfirm(ResetConfirmRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        $code = $request->input("code");
        $password = $request->input("password");

        try {
            return $this->service->resetConfirm($phone, $code, $password);
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }

    }

    /**
     * Change or set user password
     *
     * @param SetPasswordRequest $request
     * @return JsonResponse
     *
     * @response array{success:true,message:string}
     */
    function setPassword(SetPasswordRequest $request): JsonResponse
    {
        $password = $request->input("password");
        try {
            $user = Auth::user();
            $user->password = Hash::make($password);
            $user->save();
            return $this->success(__("set:password:done"));

        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }
    }
}

