<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class ContactCommand
{
    public static function handel($request)
    {
        $contact = $request->input('message.contact') ?? $request->input('message.text');

        if (!$contact) {
            return false;
        }
        return true;
    }

    public function execute($request)
    {
        $chatId = $request->input('message.chat.id');
        $messageId = $request->input('message.message_id');
        $contact = $request->input('message.contact') ?? $request->input('message.text');

        if (isset($contact['phone_number'])) {
            $phoneNumber = $contact['phone_number'];
            $firstName = $contact['first_name'];
            (new ServicesCommand())->execute($request);
            return true;
        }

        $message = 'Kontaktni yubormadingiz. Iltimos, tugmani bosing va kontakt yuboring.';

        TelegramBotHelper::sendMessage($chatId, $message);
    }
}
