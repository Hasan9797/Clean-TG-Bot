<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

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

            $response = (new ServicesCommand())->getServices($chatId, $messageId);

            if(!empty($response)){
                CacheService::updateCache("contact_$chatId", $phoneNumber);
            }

            return true;
        }

        $message = 'Kontaktni yubormadingiz. Iltimos, tugmani bosing va kontakt yuboring.';

        TelegramBotHelper::sendMessage($chatId, $message);
    }
}
