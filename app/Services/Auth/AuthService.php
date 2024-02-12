<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidConfirmationCodeException;
use App\Exceptions\IsBlockException;
use App\Exceptions\IsExpiredException;
use App\Exceptions\SmsNotFoundException;
use App\Http\Controllers\BaseController;
use App\Models\PendingUser;
use App\Models\User;
use App\Services\Sms\SmsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use BaseController;

    /**
     * Confirm otp code
     *
     * @throws IsBlockException
     * @throws SmsNotFoundException
     * @throws InvalidConfirmationCodeException
     * @throws IsExpiredException
     * @author Azamov Samandar
     */
    public function confirm($phone, $code): JsonResponse
    {
        $res = SmsService::checkConfirm($phone, $code);
        if ($res) {
            $user = User::query()->where(['phone' => $phone]);
            if (!$user->exists()) {
                $pendingUser = PendingUser::query()->where(['phone' => $phone])->first();
                if (!$pendingUser) {
                    return $this->error(__("phone:not:found"));
                }
                $user = User::query()->create([
                    'phone' => $phone,
                    "name" => $pendingUser->name,
                    "password" => $pendingUser->password
                ]);

            } else {
                $user = $user->first();
            }

            $token = $user->createToken(Carbon::now()->format("d.m.Y H:i"))->plainTextToken;
            return $this->success(
                message: __("sms.confirm"),
                data: [
                    "token" => $token
                ]
            );
        } else {
            return $this->error(__("invalid:error"));
        }
    }

    /**
     * Login
     *
     * @throws Exception
     */
    public function login($phone, $password): string
    {
        $user = User::query()->where(['phone' => $phone])->first();

        if ($user->password == null or !Hash::check($password, $user->password)) {
            throw new Exception(__("invalid:password"));
        }
        return $user->createToken("Base")->plainTextToken;
    }

    /**
     * Reset password otp confirm
     *
     * @param $phone
     * @param $code
     * @param $password
     * @return JsonResponse
     * @throws InvalidConfirmationCodeException
     * @throws IsBlockException
     * @throws IsExpiredException
     * @throws SmsNotFoundException*@throws Exception
     * @throws Exception
     * @author Azamov Samandar
     *
     */
    public function resetConfirm($phone, $code, $password): JsonResponse
    {
        $check = SmsService::checkConfirm($phone, $code);
        if ($check) {
            User::query()->where(['phone' => $phone])->update(['password' => Hash::make($password)]);
            return $this->success(__("reset:password:done"));
        }
        throw new Exception(__("reset:password:error"));
    }
}
