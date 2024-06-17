<?php

if (!function_exists('generateCardNumber')) {
    function generateCardNumber(): string
    {
        do {
            $number = mt_rand(1000000000000000, 9999999999999999);

        } while (!isValidCardNumber($number));

        return $number;
    }
}

if (!function_exists('isValidCardNumber')) {
    function isValidCardNumber($cardNumber): bool
    {
        // Convert the card number to a string to ensure consistency
        $cardNumber = (string)$cardNumber;

        // Check if the card number has exactly 16 digits
        if (!preg_match('/^\d{16}$/', $cardNumber)) {
            return false;
        }

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
