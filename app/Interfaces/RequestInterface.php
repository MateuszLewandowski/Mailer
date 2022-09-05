<?php

namespace App\Interfaces;

interface RequestInterface
{
    public static function getPendingRequest(string $uuid): object|null;
}
