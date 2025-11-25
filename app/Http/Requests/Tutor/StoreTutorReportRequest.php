<?php

namespace App\Http\Requests\Tutor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTutorReportRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'month' => ['required', 'string', 'max:50'],
            'period_from' => ['nullable', 'date'],
            'period_to' => ['nullable', 'date', 'after_or_equal:period_from'],
            'content' => ['required', 'string'],
            'summary' => ['nullable', 'string'],
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
            'rating' => ['required', 'integer', 'min:1', 'max:10'],
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
            'title.required' => 'Please provide a title for the report.',
            'month.required' => 'Please specify the month for this report.',
            'content.required' => 'Report content is required.',
            'student_id.required' => 'Please select a student.',
            'student_id.exists' => 'The selected student must be assigned to you.',
            'rating.required' => 'Please provide a rating for the student.',
            'rating.min' => 'Rating must be between 1 and 10.',
            'rating.max' => 'Rating must be between 1 and 10.',
        ];
    }
}
