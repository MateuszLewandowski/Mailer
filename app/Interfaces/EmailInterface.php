<?php

namespace App\Interfaces;

interface EmailInterface
{
    public function send(string $to, string $name, string $phone, string $email, string $text, array $approvals = []);
}
