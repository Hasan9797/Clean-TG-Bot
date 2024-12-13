<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use App\Services\CacheService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Log;

class LocationCommand
{
    public static function handel($request)
    {
        $location = $request->input('message.location') ?? $request->input('message.text');

        if (!$location || trim($location) != 'Oldingi manzilga') {
            return false;
        }
        return true;
    }

    public function execute($request)
    {

        try {
            $chatId = $request->input('message.chat.id');
            $messageId = $request->input('message.message_id');
            $location = $request->input('message.location') ?? $request->input('message.text');

            if(is_string($location)){
               $location = UserService::getLocationByChatId($chatId);
               if(empty($location)){
                 TelegramBotHelper::sendLocationRequest($chatId, "Oldingi manzil mavjudemas! \nIltimos qaytadan manzilinggizni yuboring 👇");
                 return false;
               }
            }

            $userLocation = [
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
            ];

            UserService::clientCreateAndUpdate($chatId, $userLocation);

            $response = (new ServicesCommand())->getServices($chatId, $messageId);


            return true;
        } catch (\Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            TelegramBotHelper::sendMessage(6900325674, 'LocationCommand da Xatolik: ' . $th->getMessage());
            return false;
        }
    }
}
