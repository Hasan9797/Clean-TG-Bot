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

            if (!empty($user) && ((is_array($location) && isset($location['latitude'], $location['longitude'])) || is_string($location))) {

                $data = [
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                ];

                if (is_string($location)) {
                    $data = [
                        'latitude' => $user->latitude,
                        'longitude' => $user->longitude,
                    ];
                }

                (intval($user->status) == UserStatusEnum::CREATE) ?
                    User::create(array_merge($data, [
                        'telegram_first_name' => $user->telegram_first_name,
                        'telegram_username' => $user->telegram_username,
                        'chat_id' => $chatId,
                        'phone' => $user->phone,
                        'status' => UserStatusEnum::PENDING,
                    ])) :
                    $user->update($data);

                (new ServicesCommand())->getServices($chatId, $messageId);
                return true;
            }

            $message = "Sizda ma'lumotlar topilmadi. Iltimos tilni tanlang";
            $cachLanguage = Cache::get("language_$chatId", 'lang_uz');

            if ($cachLanguage == 'lang_ru') {
                $message = 'Ваши данные не найдены. Пожалуйста, выберите язык:';
            }
            StartCommand::sendLanguageButtons($chatId, $message);
            return false;
        } catch (\Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            TelegramBotHelper::sendMessage(6900325674, 'LocationCommand da Xatolik: ' . $th->getMessage());
            return false;
        }
    }
}
