<?php

namespace App\Http\Requests\Tutor;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'month' => 'required|string|max:50',
            'period_from' => 'nullable|date',
            'period_to' => 'nullable|date|after_or_equal:period_from',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:10',
            'status' => 'nullable|in:draft,submitted',
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
