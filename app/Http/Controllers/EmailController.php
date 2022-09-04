<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitializeRequest;
use App\Http\Requests\SendEmailRequest;
use App\Interfaces\CaptchaInterface;
use App\Interfaces\EmailInterface;
use App\Models\DTO\CaptchaDTO;
use Illuminate\Support\Str;
use App\Models\Request;
use App\Services\MetaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends Controller
{
    public function __construct(
        private EmailInterface $emailService,
        private CaptchaInterface $captchaService,
        private MetaService $metaService,
    ) {
    }

    public function initialize(InitializeRequest $request): JsonResponse
    {
        DB::beginTransaction();
        if (!$pending_request = Request::create(['uuid' => Str::uuid()])) {
            DB::rollBack();
            return response()->error('err');
        }
        $meta = $this->metaService->store($request, $pending_request->id);
        $captcha = new CaptchaDTO($this->captchaService->generate($pending_request->id));
        if (!$meta or !$captcha) {
            DB::rollBack();
            return response()->error('err');
        }
        DB::commit();
        return response()->apiResponse(
            status: Response::HTTP_OK,
            message: null,
            data: [
                'uuid' => $pending_request->uuid,
                'captcha' => $captcha,
            ],
        );
    }

    public function send(SendEmailRequest $request): JsonResponse
    {
        return $this->emailService->send(...$request->only([
            'to', 'name', 'phone', 'email', 'text', 'approvals',
        ]))
            ? response()->apiResponse(status: Response::HTTP_OK, message: __('message.success'))
            : response()->error(status: Response::HTTP_INTERNAL_SERVER_ERROR, message: __('message.error'));
    }
}
