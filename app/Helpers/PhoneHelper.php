<?php

namespace App\Helpers;

use Telegram\Bot\Laravel\Facades\Telegram;

class PhoneHelper
{

    public static function isValidPhoneNumber(string $phoneNumber): bool
    {
        $digits = preg_replace('/\D/', '', $phoneNumber);

        $length = strlen($digits);
        return $length === 12 || $length === 9;
    }
}
