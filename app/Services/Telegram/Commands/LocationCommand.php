<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class LocationCommand
{
    public static function handel($request)
    {
        $location = $request->input('message.location') ?? $request->input('message.text');

        if (!$location) {
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

            $response = (new ServicesCommand())->getServices($chatId, $messageId);

            if (!empty($response)) {
                CacheService::updateCache("location_$chatId", $location);
            }

            return true;
        } catch (\Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            TelegramBotHelper::sendMessage(6900325674, 'Xatolik yuz berdi: ' . $th->getMessage());
            return false;
        }
    }

    // public static function sendLocation($chatId, $location){
    //     $message = 'Sizning joylashuvingiz: '. $location;
    //     TelegramBotHelper::sendMessage($chatId, $message);
    // }
}
