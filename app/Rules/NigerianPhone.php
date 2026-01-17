<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NigerianPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Allow empty values (use 'required' rule separately if needed)
        if (empty($value)) {
            return;
        }

        // Nigerian phone number pattern: starts with 070, 080, 081, 090, or 091, followed by 8 digits
        $pattern = '/^(070|080|081|090|091)\d{8}$/';

        if (!preg_match($pattern, $value)) {
            $fail('The :attribute must be a valid Nigerian phone number (e.g., 08012345678).');
        }
    }
}
