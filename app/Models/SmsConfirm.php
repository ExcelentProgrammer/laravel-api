<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * @property mixed $expired_at
 * @property mixed $unblocked_at
 * @property int $try_count
 * @property int $resend_count
 * @property mixed $resend_unblock_at
 */
class SmsConfirm extends Model
{
    use HasFactory;

    const SMS_EXPIRY_SECONDS = 120;
    const RESEND_BLOCK_MINUTES = 10;
    const TRY_BLOCK_MINUTES = 2;
    const RESEND_COUNT = 5;
    const TRY_COUNT = 10;

    protected $fillable = [
        "code",
        "try_count",
        "resend_count",
        "phone",
        "expired_at",
        "unblocked_at",
        "resend_unblock_at"
    ];


    function syncLimits(): void
    {
        if ($this->resend_count >= self::RESEND_COUNT) {
            $this->try_count = 0;
            $this->resend_count = 0;
            $this->resend_unblock_at = Date::now()->addMinutes(self::RESEND_BLOCK_MINUTES);
            $this->save();
        } elseif ($this->try_count >= self::TRY_COUNT) {
            $this->try_count = 0;
            $this->unblocked_at = Date::now()->addMinutes(self::TRY_BLOCK_MINUTES);
            $this->save();
        }

        if ($this->resend_unblock_at != null and $this->resend_unblock_at < Date::now()) {
            $this->resend_unblock_at = null;
            $this->save();
        }

        if ($this->unblocked_at != null and $this->unblocked_at < Date::now()) {
            $this->unblocked_at = null;
            $this->save();
        }
    }

    function isExpired(): bool
    {
        return Date::make($this->expired_at)->timestamp < Date::now()->timestamp;
    }

    function isBlock(): bool
    {
        if ($this->unblocked_at == null) {
            return false;
        }
        return true;
    }

    function resetLimits(): void
    {
        $this->try_count = 0;
        $this->resend_count = 0;
        $this->unblocked_at = null;
        $this->save();

    }

    function interval($time): \DateInterval
    {
        $unblocked_at = Date::make($time);
        $now = Date::now();
        return $now->diff($unblocked_at);
    }
}
