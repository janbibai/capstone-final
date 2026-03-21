<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StorePatientApiRequest extends FormRequest
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
                Rule::unique('appointments', 'schedule_time')->where(function ($query) {
                    return $query->where('schedule', request('schedule'))->where('status', '!=', 'cancelled');
                })
            ],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:30',
            'address' => 'nullable|string|max:50',
            'valid_id' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_time.unique' => 'The selected time slot is already booked. Please choose another time.',
            'valid_id.image' => 'The ID must be an image file.',
            'valid_id.mimes' => 'The ID must be a JPEG or PNG file.',
            'valid_id.max' => 'The ID image must not exceed 2MB.',
        ];
    }

    /**
     * Return validation errors as JSON instead of redirecting.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
