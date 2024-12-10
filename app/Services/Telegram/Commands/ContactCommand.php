<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
class ContactCommand
{
    public static function handel($request)
    {
        $contact = $request->input('message.contact');

        if (!$contact) {
            return false;
        }
        return true;
    }

    public function execute($request)
    {
        $chatId = $request->input('message.chat.id');
        $messageId = $request->input('message.message_id');
        $contact = $request->input('message.contact');  // User contactni olish

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
