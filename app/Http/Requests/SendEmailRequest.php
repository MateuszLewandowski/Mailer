<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'to' => 'required|email',
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email',
            'text' => 'required|string',
            'approvals' => 'array|nullable',
            'approvals.*' => 'string|distinct',
        ];
    }
}
