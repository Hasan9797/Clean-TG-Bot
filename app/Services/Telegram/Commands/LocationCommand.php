<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use App\Services\User\UserService;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class LocationCommand
{
    public static function handel($request)
    {
        $location = $request->input('message.location') ?? $request->input('message.text');

        if (is_array($location) && isset($location['latitude'], $location['longitude'])) {
            return true;
        }

        if (is_string($location) && trim($location) === 'Oldingi manzilga') {
            return true;
        }

        return false;
    }


    public function execute($request)
    {

        try {
            $chatId = $request->input('message.chat.id');
            $messageId = $request->input('message.message_id');
            $location = $request->input('message.location') ?? $request->input('message.text');
            $userLocation = [];

            if (!empty($location) && is_array($location)) {
                $latitude = $location['latitude'] ?? null;
                $longitude = $location['longitude'] ?? null;

                if (is_null($latitude) || is_null($longitude)) {
                    TelegramBotHelper::sendLocationRequest($chatId, "Location ma'lumotlari yetarli emas. Iltimos qayta jo'nating");
                    return false;
                }

                $userLocation = [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ];
            } else {

                $user = UserService::getLocationByChatId($chatId);

                if (empty($user) || empty($user->latitude) || empty($user->longitude)) {
                    StartCommand::sendLanguageButtons($chatId, "Sizning ma'lumotinggiz topilmadi! Iltimos tilni tanlang:\n Ваша информация не найдена! Пожалуйста, укажите язык: 👇");
                    return false;
                }

                $userLocation = [
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                ];
            }
            UserService::clientCreateOrUpdate($chatId, $userLocation);

            (new ServicesCommand())->getServices($chatId, $messageId);

            return true;
        } catch (\Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            TelegramBotHelper::sendMessage(6900325674, 'LocationCommand da Xatolik: ' . $th->getMessage());
            return false;
        }
    }
}
