<?php

namespace App\Services;

use App\Interfaces\CaptchaInterface;
use Illuminate\Support\Facades\Mail;
use App\Interfaces\EmailInterface;
use App\Mail\ContactFormEmail;
use Throwable;

final class EmailService implements EmailInterface
{
    public function __construct(
        private CaptchaInterface $captchaService,
    ) {
    }

    public function send(string $to, string $name, string $phone, string $email, string $text, array $approvals = [])
    {
        try {
            return Mail::to($to)->send(
                new ContactFormEmail($name, $phone, $email, $text, $approvals)
            );
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
