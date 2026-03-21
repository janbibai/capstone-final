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
            'schedule_time' => [
                'required',
                'date_format:H:i',
                \Illuminate\Validation\Rule::unique('appointments', 'schedule_time')->where(function ($query) {
                    return $query->where('schedule', request('schedule'))->where('status', '!=', 'cancelled');
                })
            ],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:30|unique:patients,email',
            'address' => 'nullable|string|max:50',
            'valid_id' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // 'patient_number' => 'nullable|string|unique:patients,patient_number',
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_time.unique' => 'The selected time slot is already booked. Please choose another time.',
            'valid_id.required' => 'Please upload a valid government-issued ID.',
            'valid_id.image' => 'The ID must be an image file.',
            'valid_id.mimes' => 'The ID must be a JPEG or PNG file.',
            'valid_id.max' => 'The ID image must not exceed 2MB.',
        ];
    }
}