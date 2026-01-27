<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'=>'required|string|max:50',
            'middle_name'=>'nullable|string|max:50',
            'last_name'=>'required|string|max:50',
            'date_of_birth'=>'required|date',
            'gender'=>'required|in:male, female, other',
            'phone'=>'required|digits:11',
            'email'=>'required|email|unique',
            'address'=>'required|string|max:100',
            'patient_number'=>'required|digits:11',
        ];
    }
}
