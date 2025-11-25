<?php

namespace App\Http\Requests\Tutor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitAttendanceRequest extends FormRequest
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
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
                Rule::exists('students', 'id')->where(function ($query) {
                    // Ensure the student belongs to this tutor
                    $tutor = $this->user()->tutor;
                    if ($tutor) {
                        $query->where('tutor_id', $tutor->id);
                    }
                }),
            ],
            'class_date' => ['required', 'date'],
            'class_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:300'],
            'topic' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::in(['present', 'absent', 'late', 'excused'])],
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
            'student_id.required' => 'Please select a student.',
            'student_id.exists' => 'The selected student must be assigned to you.',
            'class_date.required' => 'Please specify the class date.',
            'class_date.date' => 'Please provide a valid date.',
            'class_time.required' => 'Please specify the class time.',
            'class_time.date_format' => 'Class time must be in HH:MM format (e.g., 14:30).',
            'duration_minutes.required' => 'Please specify the class duration.',
            'duration_minutes.min' => 'Duration must be at least 1 minute.',
            'duration_minutes.max' => 'Duration cannot exceed 300 minutes (5 hours).',
        ];
    }
}
