<?php

namespace App\Http\Requests\Tutor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the authenticated user is a tutor
        return $this->user() && $this->user()->hasRole('tutor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'day' => [
                'required',
                'string',
                Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'day.required' => 'Please select a day.',
            'day.in' => 'Please select a valid day of the week.',
            'start_time.required' => 'Please specify the start time.',
            'start_time.date_format' => 'Start time must be in HH:MM format (e.g., 14:30).',
            'end_time.required' => 'Please specify the end time.',
            'end_time.date_format' => 'End time must be in HH:MM format (e.g., 16:30).',
            'end_time.after' => 'End time must be after start time.',
            'notes.max' => 'Notes cannot exceed 500 characters.',
        ];
    }
}
