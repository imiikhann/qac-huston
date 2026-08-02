<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source' => 'sometimes|in:website,ai-chat,referral,social-media,search,other',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'service_interest' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:2000',
        ];
    }
}
