<?php

namespace App\Http\Requests\Tutor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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
            'student_id' => 'required|exists:students,id',
            'title' => 'nullable|string|max:255',
            'month' => 'required|date_format:Y-m',
            'period_from' => 'nullable|date',
            'period_to' => 'nullable|date|after_or_equal:period_from',
            'content' => 'nullable|string',
            'summary' => 'nullable|string|max:1000',
            'progress_summary' => 'required|string',
            'strengths' => 'required|string',
            'weaknesses' => 'required|string',
            'next_steps' => 'required|string',
            'attendance_score' => 'required|integer|min:0|max:100',
            'performance_rating' => 'required|in:excellent,good,average,poor',
            'rating' => 'nullable|integer|min:1|max:10',
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
            'student_id.exists' => 'The selected student is invalid.',
            'title.required' => 'Report title is required.',
            'month.required' => 'Report month is required.',
            'content.required' => 'Report content is required.',
            'period_to.after_or_equal' => 'End date must be after or equal to start date.',
            'rating.min' => 'Rating must be between 1 and 10.',
            'rating.max' => 'Rating must be between 1 and 10.',
        ];
    }
}
