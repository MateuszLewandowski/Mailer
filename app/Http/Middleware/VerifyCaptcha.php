<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Captcha as CaptchaModel;
use App\Models\Request as RequestModel;
use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyCaptcha
{
    private array $excluded = [
        '/api/sm/captcha/refresh'
    ];
    /**
     * Handle an incoming request.
     * @todo HttpException.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->getRequestUri(), $this->excluded)) {
            return $next($request);
        }
        if (!$captcha = $request->get('captcha', false)) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return CaptchaService::verify(obtained: $captcha, expected: $request->pending_request['result'])
            ? $next($request)
            : throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
