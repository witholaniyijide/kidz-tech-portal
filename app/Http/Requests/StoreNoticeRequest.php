<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoticeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only managers, admins, and directors can create notices
        return $this->user() && in_array($this->user()->role, ['manager', 'admin', 'director']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent,important,general,reminder',
            'visible_to' => 'required|array',
            'visible_to.*' => 'in:director,manager,admin,tutor,parent,all',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // If user is a manager, ensure they're not creating director-only notices
            if ($this->user()->role === 'manager') {
                $visibleTo = $this->input('visible_to', []);

                // Managers cannot create director-only notices
                if ($visibleTo === ['director']) {
                    $validator->errors()->add('visible_to', 'Managers cannot create notices visible only to directors.');
                }

                // Ensure manager is included in visible_to
                if (!in_array('manager', $visibleTo) && !in_array('all', $visibleTo)) {
                    // Automatically add manager to visible_to
                    $this->merge([
                        'visible_to' => array_merge($visibleTo, ['manager'])
                    ]);
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The notice title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'content.required' => 'The notice content is required.',
            'priority.required' => 'Please select a priority level.',
            'priority.in' => 'The selected priority is invalid.',
            'visible_to.required' => 'Please select at least one audience.',
            'visible_to.*.in' => 'One or more selected audiences are invalid.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid.',
        ];
    }
}
