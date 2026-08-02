<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => 'required|exists:membership_programs,id',
            'customer_id' => 'nullable|exists:customers,id',
            'customer.name' => 'required_without:customer_id|string|max:255',
            'customer.email' => 'required_without:customer_id|email|max:255',
            'customer.phone' => 'required_without:customer_id|string|max:30',
            'vehicle.make' => 'nullable|string|max:255',
            'vehicle.model' => 'nullable|string|max:255',
            'vehicle.year' => 'nullable|integer|min:1900|max:2100',
            'vehicle.vin' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'payment_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,doc,docx,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.required' => 'Please select a membership program.',
            'program_id.exists' => 'The selected membership program does not exist.',
            'customer.name.required_without' => 'A name is required to enroll.',
            'customer.email.required_without' => 'An email address is required to enroll.',
            'customer.email.email' => 'Please enter a valid email address.',
            'customer.phone.required_without' => 'A phone number is required to enroll.',
            'payment_proof.mimes' => 'Payment proof must be a PDF, JPG, JPEG, DOC, DOCX, or PNG file.',
            'payment_proof.max' => 'Payment proof must not exceed 5MB.',
        ];
    }
}
