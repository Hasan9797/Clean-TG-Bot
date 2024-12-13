<?php

namespace App\Services\Telegram\Commands;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\PhoneAndDateHelper;
use App\Helpers\TelegramBotHelper;
use App\Services\CacheService;
use App\Services\User\UserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        try {
            $chatId = $request->input('message.chat.id');
            $phoneNumber = Arr::get($request->input('message.contact'), 'phone_number', $request->input('message.text'));
            $message = 'Manzilinggizni yuborish uchun quyidagi tugmani bosing:';

            $cachLanguage = Cache::get("language_$chatId", 'lang_uz');

            if ($cachLanguage == 'lang_ru') {
                $message = 'Нажмите кнопку ниже, чтобы отправить Manzilinglife:';
            }

            $firstName = $request->input('message.from.first_name');
            $userName = $request->input('message.from.username');

            if (PhoneAndDateHelper::isValidPhoneNumber($phoneNumber)) {

                $user = [
                    'telegram_first_name' => $firstName,
                    'telegram_username' =>  $userName,
                    'chat_id' => $chatId,
                    'phone' => $phoneNumber,
                    'status' => UserStatusEnum::PINDING,
                ];

                UserService::clientCreateAndUpdate($chatId, $user);

                TelegramBotHelper::sendLocationRequest($chatId, $message);

                return true;
            }

            $message = 'Kontaktni yubormadingiz. Iltimos, tugmani bosing va kontakt yuboring.';

            TelegramBotHelper::sendMessage($chatId, $message);
        } catch (\Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            TelegramBotHelper::sendMessage(6900325674, 'ContactCommand da Xatolik: ' . $th->getMessage());
            return false;
        }
    }
}
