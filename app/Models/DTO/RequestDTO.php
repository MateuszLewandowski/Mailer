<?php

namespace App\Models\DTO;

class RequestDTO
{
    public readonly string $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }
}
