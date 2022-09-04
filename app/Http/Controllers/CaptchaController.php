<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefreshCaptchaRequest;
use Illuminate\Http\JsonResponse;

class CaptchaController extends Controller
{
    public function refresh(RefreshCaptchaRequest $request): JsonResponse
    {

    }
}
