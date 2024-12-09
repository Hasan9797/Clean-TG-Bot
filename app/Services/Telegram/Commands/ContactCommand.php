<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Illuminate\Support\Facades\Cache;

class CalendarCommand
{
    public static function handel($request)
    {
        $message = $request->input('callback_query.data');

        if (preg_match('#date:|next_week#', $message)) {
            return true;
        }
        return false;
    }


    public function execute($request) {}


    public function requestPhoneNumber($chatId)
    {
        $message = 'Iltimos, telefon raqamingizni yuboring yoki quyidagi tugma orqali o\'zingizning kontaktni yuboring:';

        // Telefon raqamini yuborish uchun inline button
        $inlineKeyboard = [
            [
                'text' => 'Kontaktni yuborish',
                'request_contact' => true // Kontaktni so'rash
            ]
        ];

        // Foydalanuvchiga xabar yuborish
        TelegramBotHelper::sendMessage($chatId, $message, $inlineKeyboard);
    }
}
