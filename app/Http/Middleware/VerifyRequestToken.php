<?php

namespace App\Http\Middleware;

use App\Services\RequestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyRequestToken
{
    /**
     * Handle an incoming request.
     *
     * @todo HttpException.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $uuid = $request->header('uuid', false)) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if (! $pending_request = RequestService::getPendingRequest($uuid)) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $request->merge(['pending_request' => (array) $pending_request]);

        return $next($request);
    }
}
