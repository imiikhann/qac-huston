<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|max:4000',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please include a message.',
            'message.max' => 'Your message cannot exceed 4000 characters.',
        ];
    }
}
