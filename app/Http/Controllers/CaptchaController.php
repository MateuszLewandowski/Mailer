<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefreshCaptchaRequest;
use App\Interfaces\CaptchaInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CaptchaController extends Controller
{
    public function __construct(
        private CaptchaInterface $captchaService,
    ) {
    }

    public function refresh(RefreshCaptchaRequest $request): JsonResponse
    {
        return response()->apiResponse(
            status: Response::HTTP_RESET_CONTENT,
            message: null,
            data: [
                'captcha' => $this->captchaService->refresh(id: $request->get('pending_request')['captcha_id']),
            ],
        );
    }
}
