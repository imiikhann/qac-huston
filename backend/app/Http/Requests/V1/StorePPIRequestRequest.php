<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StorePPIRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'vehicle_make' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|date_format:H:i',
            'tier' => 'sometimes|in:basic,standard,comprehensive',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
