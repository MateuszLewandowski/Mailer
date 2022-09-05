<?php

namespace App\Interfaces;

use App\Models\Meta;
use Illuminate\Foundation\Http\FormRequest;

interface MetaInterface
{
    public function store(FormRequest $request, string $id): Meta;

    public function update(int $id, int $content_size): bool;
}
