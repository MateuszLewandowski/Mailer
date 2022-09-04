<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

final class RequestService
{
    public function __construct(
    ) {
    }

    public static function getPendingRequest(string $uuid)
    {
        try {
            return DB::table('requests')
                ->join('captcha', 'requests.id', '=', 'captcha.request_id')
                ->selectRaw('requests.*, captcha.result')
                ->where('requests.uuid', '=', $uuid)
                ->where('requests.completed_at', '=', null)
                ->whereDate('created_at', '<=', Carbon::now()->subMinutes(15))
                ->first();
            } catch (Throwable $e) {
            throw $e;
        }
    }
}
