<?php

namespace App\Services;

use App\Interfaces\RequestInterface;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

final class RequestService implements RequestInterface
{
    public function __construct(
    ) {
    }

    public static function getPendingRequest(string $uuid): object|null
    {
        try {
            return DB::table('requests')
                ->join('captcha', 'requests.id', '=', 'captcha.request_id')
                ->selectRaw('requests.*, captcha.result, captcha.id as captcha_id')
                ->where('requests.uuid', '=', $uuid)
                ->where('requests.completed_at', '=', null)
                ->whereDate('created_at', '<=', Carbon::now()->subMinutes(15))
                ->first();
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public static function setCompleted(int $id): bool
    {
        try {
            return Request::where('id', '=', $id)
                ->update([
                    'completed_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
