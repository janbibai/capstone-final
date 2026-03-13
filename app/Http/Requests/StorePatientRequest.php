<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:30',
            'middle_name' => 'nullable|string|max:30',
            'last_name' => 'required|string|max:30',
            'service_id' => 'required|exists:services,id',
            'schedule' => 'required|date|after_or_equal:today',
            'schedule_time' => 'required|date_format:H:i',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:30',
            'address' => 'nullable|string|max:50',
            // 'patient_number' => 'nullable|string|unique:patients,patient_number',
        ];
    }
}