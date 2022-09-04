<?php

namespace App\Interfaces;

interface CaptchaInterface
{
    public function generate(int $request_id): array;
    public function refresh(string $uuid): array;
}
