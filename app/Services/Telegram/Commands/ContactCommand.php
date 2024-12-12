<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\PhoneHelper;
use App\Helpers\TelegramBotHelper;
use App\Services\CacheService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Objects\Contact;

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
        $phoneNumber = Arr::get($request->input('message.contact'), 'phone_number', $request->input('message.text'));

        if (PhoneHelper::isValidPhoneNumber($phoneNumber)) {

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
