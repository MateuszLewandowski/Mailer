<?php

namespace App\Services;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Meta;
use Throwable;

final class MetaService
{
    public function __construct(
    ) {
    }

    public function store(FormRequest $request, string $id): Meta {
        try {
            return Meta::create([
                'request_id' => $id,
                'ip_address' => $request->ip() ?? null,
                'user_agent' => $request->header('user-agent') ?? null,
                'fingerprint' => $request->fingerprint() ?? null,
            ]);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function update(int $id, int $content_size): Meta {
        try {
            return Meta::where('id', '=', $id)
                ->update([
                    'content_size' => $content_size
                ]);
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
