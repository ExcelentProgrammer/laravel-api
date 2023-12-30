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
use App\Models\User;
use App\Services\Sms\SmsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use BaseController;

    public function __construct()
    {
        $this->middleware("auth:sanctum")->only(["me", "setPassword"]);
    }

    function register(RegisterRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        try {
            $res = SmsService::sendConfirm($phone);
            return $this->success(__("sms.send:success"), data: [
                "provider" => $res === "telegram" ? $res : "sms",
            ]);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    function confirm(ConfirmRequest $request): JsonResponse
    {
        $code = $request->input("code");
        $phone = $request->input("phone");
        try {
            $res = SmsService::checkConfirm($phone, $code);
            if ($res) {
                $user = User::query()->where(['phone' => $phone]);
                if (!$user->exists()) {
                    $user = User::query()->create([
                        'phone' => $phone
                    ]);
                } else {
                    $user = $user->first();
                }

                $token = $user->createToken(Carbon::now()->format("d.m.Y H:i"))->plainTextToken;
                return $this->success(
                    message: __("sms.confirm"),
                    data: [
                        "provider" => $res === "telegram" ? $res : "sms",
                        "token" => $token
                    ]
                );
            } else {
                return $this->error(__("invalid:error"));
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    function login(LoginRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        $password = $request->input("password");

        $user = User::query()->where(['phone' => $phone])->first();

        if ($user->password == null or !Hash::check($password, $user->password)) {
            return $this->error(__("invalid:password"));
        }
        $token = $user->createToken("Base")->plainTextToken;
        return $this->success(data: [
            "token" => $token
        ]);
    }

    function me(): JsonResponse
    {

        return $this->success(data: MeResource::make(Auth::user()));
    }

    function update(UserUpdateRequest $request): JsonResponse
    {
        try {

            $user = Auth::user();
            $user->fill($request->validated());
            $user->save();
            return $this->success(__("user.update:profile"));

        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    function reset(ResetRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        try {
            $res = SmsService::sendConfirm($phone);
            return $this->success(__("sms.send:success"), data: [
                "provider" => $res === "telegram" ? $res : "sms"
            ]);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    function resetConfirm(ResetConfirmRequest $request): JsonResponse
    {
        $phone = $request->input("phone");
        $code = $request->input("code");
        $password = $request->input("password");

        try {
            $check = SmsService::checkConfirm($phone, $code);
            if ($check == true) {
                User::query()->where(['phone' => $phone])->update(['password' => Hash::make($password)]);
                return $this->success(__("reset:password:done"));
            }
            return $this->error(__("reset:password:error"));

        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }

    }

    function setPassword(SetPasswordRequest $request): JsonResponse
    {
        $password = $request->input("password");
        try {
            $user = Auth::user();
            $user->password = Hash::make($password);
            $user->save();
            return $this->success(__("set:password:done"));

        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }


}

