<?php

namespace Tests\Traits;

trait HeaderBuilder
{
    private function getHeaders() {
        return [
            'API-Token' => config('api.token'),
        ];
    }
}
