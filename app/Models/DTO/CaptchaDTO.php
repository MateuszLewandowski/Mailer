<?php

namespace App\Models\DTO;

class CaptchaDTO
{
    public readonly string $operation;
    public readonly int $x;
    public readonly int $y;
    public readonly int $result;

    public function __construct(array $properties)
    {
        foreach ($properties as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }
            $this->{$key} = $value;
        }
    }
}
