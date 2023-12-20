<?php

namespace App\Jobs;

use App\Services\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $smsConfirm;

    public function __construct($smsConfirm)
    {
        $this->smsConfirm = $smsConfirm;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $smsService = new Sms\SendService();
            $smsService->sendSms($this->smsConfirm->phone, __("confirm:code", ['code' => $this->smsConfirm->code]));
        } catch (\Exception $e) {
            Log::error("SMS: {$e->getMessage()}");
        }
    }
}
