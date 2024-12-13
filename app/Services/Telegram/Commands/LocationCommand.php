<?php

namespace App\Services\Telegram\Commands;

use App\Enums\UserStatusEnum;
use App\Helpers\TelegramBotHelper;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Cache;
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
            $user = UserService::getByChatId($chatId);

            if (empty($user)) {
                $this->sendLanguageSelection($chatId);
                return false;
            }

            $data = $this->prepareLocationData($user, $location);
            if (!$data) {
                StartCommand::sendLanguageButtons($chatId, "Iltimos, to'g'ri joylashuvni yuboring.");
                return false;
            }

            $this->createOrUpdateUser($user, $data, $chatId);
            (new ServicesCommand())->getServices($chatId, $messageId);

            return true;
        } catch (\Throwable $th) {
            $this->handleError($th);
            return false;
        }
    }

    private function prepareLocationData($user, $location)
    {
        if (is_array($location) && isset($location['latitude'], $location['longitude'])) {
            return [
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
            ];
        } elseif (is_string($location)) {
            return [
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
            ];
        }
        return null;
    }

    private function createOrUpdateUser($user, $data, $chatId)
    {
        $userData = array_merge($data, [
            'telegram_first_name' => $user->telegram_first_name,
            'telegram_username' => $user->telegram_username,
            'chat_id' => $chatId,
            'phone' => $user->phone,
            'status' => UserStatusEnum::PENDING,
        ]);

        (intval($user->status) == UserStatusEnum::CREATE) ? User::create($userData) : $user->update($data);
    }

    private function sendLanguageSelection($chatId)
    {
        $message = Cache::get("language_$chatId", 'lang_uz') == 'lang_ru'
            ? 'Ваши данные не найдены. Пожалуйста, выберите язык:'
            : "Sizda ma'lumotlar topilmadi. Iltimos tilni tanlang";

        StartCommand::sendLanguageButtons($chatId, $message);
    }

    private function handleError(\Throwable $th)
    {
        Log::error('Error: ' . $th->getMessage());
        TelegramBotHelper::sendMessage(6900325674, 'LocationCommand da Xatolik: ' . $th->getMessage());
    }
}
