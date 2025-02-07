<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsbnRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d{10}(\d{3})?$/', $value)) {
            $fail("The $attribute must be a valid 10 or 13-digit ISBN.");
        }
    }
}
