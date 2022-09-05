<?php

namespace App\Providers;

use App\Actions\ValidateResponseCodeAction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('apiResponse', function (mixed $status = HttpResponse::HTTP_BAD_REQUEST, ?string $message = null, array $data = [], array $headers = []): JsonResponse {
            $status = ValidateResponseCodeAction::check($status);
            $response = [
                'ok' => ($status > 100 and $status < 400) ? true : false,
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            if (! is_null($message)) {
                $response['message'] = $message;
            }
            if (! empty($data)) {
                $response['data'] = $data;
            }

            return response()->json($response, $status, $headers);
        });

        Response::macro('error', function (string $message, int|string|null $status = HttpResponse::HTTP_INTERNAL_SERVER_ERROR, array $headers = []): JsonResponse {
            return response()->json([
                'ok' => false,
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                'message' => $message,
            ], ValidateResponseCodeAction::check($status), $headers);
        });
    }
}
