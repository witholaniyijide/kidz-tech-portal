<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Student Information
            'first_name' => ['required', 'string', 'max:255'],
            'other_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'gender' => ['required', 'in:male,female,other'],
            'email' => ['nullable', 'email', 'max:255', 'unique:students,email'],
            'coding_experience' => ['nullable', 'string', 'max:1000'],
            'career_interest' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'in:active,inactive'],

            // Class Information
            'google_classroom_link' => ['required', 'url', 'max:500'],
            'class_link' => ['nullable', 'url', 'max:500'],
            'tutor_id' => ['required', 'exists:tutors,id'],
            'class_schedule' => ['nullable', 'array'],
            'class_schedule.*.day' => ['required_with:class_schedule', 'string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'class_schedule.*.time' => ['required_with:class_schedule', 'string'],
            'classes_per_week' => ['nullable', 'integer', 'in:1,2,3'],
            'total_periods' => ['nullable', 'integer', 'min:0'],
            'completed_periods' => ['nullable', 'integer', 'min:0'],

            // Father's Information
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_phone' => ['nullable', 'string', 'regex:/^(070|080|081|090|091)\d{8}$/'],
            'father_email' => ['nullable', 'email', 'max:255'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'father_location' => ['nullable', 'string', 'max:255'],

            // Mother's Information
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_phone' => ['nullable', 'string', 'regex:/^(070|080|081|090|091)\d{8}$/'],
            'mother_email' => ['nullable', 'email', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'mother_location' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'other_name' => 'other name',
            'date_of_birth' => 'date of birth',
            'tutor_id' => 'assigned tutor',
            'google_classroom_link' => 'Google Classroom link',
            'class_link' => 'class link',
            'classes_per_week' => 'classes per week',
            'total_periods' => 'total periods',
            'completed_periods' => 'completed periods',
            'father_phone' => "father's phone",
            'mother_phone' => "mother's phone",
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'father_phone.regex' => "Father's phone must be a valid Nigerian phone number (e.g., 08012345678)",
            'mother_phone.regex' => "Mother's phone must be a valid Nigerian phone number (e.g., 08012345678)",
            'date_of_birth.before' => 'Date of birth must be before today.',
            'google_classroom_link.required' => 'Google Classroom link is required.',
            'tutor_id.required' => 'Please assign a tutor to this student.',
            'tutor_id.exists' => 'The selected tutor does not exist.',
        ];
    }
}
