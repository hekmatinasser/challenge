<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CreditCardNumber implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!$this->isValidIranianCardNumber($value)) {
            $fail('The :attribute is not a valid Iranian bank card number.');
        }
    }

    private function isValidIranianCardNumber($cardNumber)
    {

        // Convert the card number to a string to ensure consistency
        $cardNumber = (string)$cardNumber;

        // Check if the card number has exactly 16 digits
        if (!preg_match('/^\d{16}$/', $cardNumber)) {
            return false;
        }

        // Perform the Luhn algorithm to validate the card number
        $checksum = 0;
        for ($i = 0; $i < 16; $i++) {
            $digit = (int)$cardNumber[$i]; // Access each character as a string
            if ($i % 2 == 0) { // Even index (0-based)
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $checksum += $digit;
        }

        return ($checksum % 10) === 0;
    }
}
