<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitializeRequest;
use App\Http\Requests\SendEmailRequest;
use App\Interfaces\CaptchaInterface;
use App\Interfaces\EmailInterface;
use App\Models\DTO\CaptchaDTO;
use App\Models\Request;
use App\Services\MetaService;
use App\Services\RequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        if (! $pending_request = Request::create(['uuid' => Str::uuid()])) {
            DB::rollBack();

            return response()->error('err');
        }
        $meta = $this->metaService->store($request, $pending_request->id);
        $captcha = new CaptchaDTO($this->captchaService->generate($pending_request->id));
        if (! $meta or ! $captcha) {
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
        $pending_request_id = $request->get('pending_request')['id'];
        if (! $this->emailService->send(...$request->validated())) {
            return response()->error(status: Response::HTTP_INTERNAL_SERVER_ERROR, message: __('message.error'));
        }
        if (! RequestService::setCompleted($pending_request_id)) {
            return response()->error(status: Response::HTTP_INTERNAL_SERVER_ERROR, message: __('message.error'));
        }
        if (! $this->metaService->update(
            $pending_request_id, mb_strlen(serialize(
                $request->validated()
            ))
        )) {
            Log::error("Request ID: {$pending_request_id}. An error occurred while updating the size of the email content.", ['Meta']);
        }

        return response()->apiResponse(status: Response::HTTP_OK, message: __('message.success'));
    }
}
