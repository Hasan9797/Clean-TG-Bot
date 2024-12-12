<?php

namespace App\Helpers;

use DateTime;

class PhoneAndDateHelper
{

    public static function isValidPhoneNumber(string $phoneNumber): bool
    {
        $digits = preg_replace('/\D/', '', $phoneNumber);

        $length = strlen($digits);
        return $length === 12 || $length === 9;
    }

    public static function isValidDate(string $date, string $format = 'Y-m-d'): bool
    {
        $dateTime = DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }
}
