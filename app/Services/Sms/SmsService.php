<?php

namespace App\Services\Sms;

use App\Exceptions\InvalidConfirmationCodeException;
use App\Exceptions\IsBlockException;
use App\Exceptions\IsExpiredException;
use App\Exceptions\SmsNotFoundException;
use App\Jobs\SendSmsJob;
use App\Models\SmsConfirm;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Date;

class SmsService
{
    /**
     * @throws IsBlockException
     */
    static function sendConfirm($phone, $job = null): mixed
    {
        $smsConfirm = SmsConfirm::query()->where(['phone' => $phone])->first();

        if ($smsConfirm === null) {
            $smsConfirm = new SmsConfirm();
        }

        $smsConfirm->syncLimits();

        if ($smsConfirm->resend_unblock_at !== null) {
            $expired = $smsConfirm->interval($smsConfirm->resend_unblock_at);
            $message = __("phone:block:resend", ['time' => $expired->i . ":" . $expired->s]);
            $exception = new IsBlockException($message);
            $exception->data = [
                "time" => $expired->i * 60 + $expired->s
            ];
            throw $exception;
        }

        $code = 1111; // TODO: Deploy qilinganda o'zgartirish kerak -> rand(1000, 9999)
//        $code = rand(1000, 9999);
        $smsConfirm->fill([
            'code' => $code,
            'try_count' => 0,
            'resend_count' => $smsConfirm->resend_count + 1,
            'phone' => $phone,
            'expired_at' => Date::now()->addSeconds(SmsConfirm::SMS_EXPIRY_SECONDS),
            "resend_unblock_at" => Date::now()->addSeconds(SmsConfirm::SMS_EXPIRY_SECONDS),
        ]);

        if (empty($smsConfirm->id)) {
            $smsConfirm->save();
        } else {
            $smsConfirm->update();
        }
        $telegram = TelegramService::sendMessage($phone, __("confirm:code", ['code' => $smsConfirm->code]));
        if ($telegram) {
            return "telegram";
        }
        if ($job == null) {
            SendSmsJob::dispatch($smsConfirm);
        } else {
            $job::dispatch($smsConfirm);
        }
        return true;
    }


    /**
     * @throws SmsNotFoundException|InvalidConfirmationCodeException
     * @throws IsExpiredException
     * @throws IsBlockException
     */
    static function checkConfirm(string|int $phone, int $code): bool
    {
        $smsConfirm = SmsConfirm::query()->where(['phone' => $phone])->first();

        if ($smsConfirm === null) {
            throw new SmsNotFoundException(__("sms:invalid:code"));
        }
        $smsConfirm->syncLimits();


        if ($smsConfirm->isExpired()) {
            throw new IsExpiredException(__("sms:time:expired"));
        }

        if ($smsConfirm->isBlock()) {
            $expired = $smsConfirm->interval($smsConfirm->unblocked_at);
            throw new IsBlockException(__("phone:block:try", ['time' => $expired->i . ":" . $expired->s]));
        }

        if ($smsConfirm->code == $code) {
            $smsConfirm->delete();
            return true;
        }


        $smsConfirm->try_count++;
        $smsConfirm->save();

        throw new InvalidConfirmationCodeException(__("sms:invalid:code"));
    }
}
