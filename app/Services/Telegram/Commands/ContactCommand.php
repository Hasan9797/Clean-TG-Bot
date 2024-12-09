<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Illuminate\Support\Facades\Cache;

class ContactCommand
{
    public static function handel($request)
    {
        $message = $request->input('callback_query.data');

        if (preg_match('#date:|next_week#', $message)) {
            return true;
        }
        return false;
    }

    public function execute($request)
    {
        $chatId = $request->input('callback_query.message.chat.id');
        $messageId = $request->input('callback_query.message.message_id');
        $contact = $request->input('callback_query.data.contact');  // User contactni olish

        // Contact mavjudligini tekshirish
        if (isset($contact['phone_number'])) {
            $phoneNumber = $contact['phone_number'];
            $firstName = $contact['first_name'];

            // Foydalanuvchining telefon raqami va nomini qayd qilish
            $message = "Sizning telefon raqamingiz: $phoneNumber\nIsmingiz: $firstName";

            // Bu yerda qo'shimcha ishlar (telefonni saqlash, bazaga yuborish va h.k.)
        } else {
            $message = 'Kontaktni yubormadingiz. Iltimos, tugmani bosing va kontakt yuboring.';
        }

        // Javobni yuborish
        TelegramBotHelper::sendMessage($chatId, $message);
    }
}
