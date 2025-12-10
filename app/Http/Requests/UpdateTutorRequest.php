<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTutorRequest extends FormRequest
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
        $tutorId = $this->route('tutor')->id ?? null;

        return [
            // Personal Information
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('tutors', 'email')->ignore($tutorId)],
            'phone' => ['required', 'string', 'regex:/^(070|080|081|090|091)\d{8}$/'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'location' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:active,inactive,on_leave'],

            // Emergency Contact
            'contact_person_name' => ['nullable', 'string', 'max:255'],
            'contact_person_relationship' => ['nullable', 'string', 'max:255'],
            'contact_person_phone' => ['nullable', 'string', 'regex:/^(070|080|081|090|091)\d{8}$/'],

            // Payment Information
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:20'],
            'account_name' => ['nullable', 'string', 'max:255'],
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
            'date_of_birth' => 'date of birth',
            'profile_photo' => 'profile photo',
            'contact_person_name' => 'emergency contact name',
            'contact_person_relationship' => 'relationship',
            'contact_person_phone' => 'emergency contact phone',
            'bank_name' => 'bank name',
            'account_number' => 'account number',
            'account_name' => 'account name',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone must be a valid Nigerian phone number (e.g., 08012345678)',
            'contact_person_phone.regex' => 'Emergency contact phone must be a valid Nigerian phone number',
            'email.unique' => 'A tutor with this email already exists.',
            'profile_photo.image' => 'Profile photo must be an image file.',
            'profile_photo.max' => 'Profile photo must not exceed 2MB.',
        ];
    }
}
