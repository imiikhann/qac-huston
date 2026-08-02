<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'customer.name' => 'required_without:customer_id|string|max:255',
            'customer.email' => 'required_without:customer_id|email|max:255',
            'customer.phone' => 'required_without:customer_id|string|max:30',
            'vehicle.make' => 'nullable|string|max:255',
            'vehicle.model' => 'nullable|string|max:255',
            'vehicle.year' => 'nullable|integer|min:1900|max:2100',
            'vehicle.vin' => 'nullable|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'requested_date' => 'required|date|after_or_equal:today',
            'requested_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'The selected customer does not exist.',
            'customer.name.required_without' => 'A customer name is required to book an appointment.',
            'customer.email.required_without' => 'A customer email is required to book an appointment.',
            'customer.email.email' => 'Please enter a valid email address.',
            'customer.phone.required_without' => 'A customer phone number is required to book an appointment.',
            'service_id.exists' => 'The selected service does not exist.',
            'requested_date.required' => 'Please select a date for your appointment.',
            'requested_date.after_or_equal' => 'Appointment date must be today or in the future.',
        ];
    }
}
