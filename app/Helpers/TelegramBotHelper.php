<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotHelper
{

    public static function inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard = [])
    {
        $response = Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => json_encode($inlineKeyboard)
        ]);
        return $response;
    }

    public static function editMessageAndInlineKeyboard($chatId, $messageId, $message, $inlineKeyboard = [])
    {
        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
            'reply_markup' => json_encode($inlineKeyboard)
        ]);
    }

    public static function sendMessage($chatId, $message)
    {
        return Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);
    }

    public static function deleteMessage($chatId, $messageId)
    {
        try {
            Telegram::deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);
        } catch (\Throwable $th) {
            Telegram::sendMessage(6900325674, $th->getMessage());
        }
    }

    public static function sendPhoneRequest($chatId, $message)
    {
        try {
            if (empty(trim($message))) {
                $message = "Iltimos, telefon raqamingizni yuboring.";
            }

            $replyKeyboard = [
                [
                    [
                        'text' => 'Отправить контакт',
                        'request_contact' => true
                    ]
                ]
            ];

            $params = [
                'chat_id' => $chatId,
                'text' => $message,
                'reply_markup' => json_encode([
                    'keyboard' => $replyKeyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
            ];

            Telegram::sendMessage($params);
        } catch (\Throwable $th) {
            self::sendMessage(6900325674, $th->getMessage());
        }
    }


    public static function sendClientRequestMessage($chatId, $user, $language)
    {
        // Maxsus belgilarni qochirish uchun funksiya
        $escapeMarkdown = function ($text) {
            $search = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
            $replace = array_map(fn($char) => '\\' . $char, $search);
            return str_replace($search, $replace, $text);
        };

        $messageTemplate = $language === 'lang_ru'
            ? "📝 *Новый клиент запросил:*\n"
            : "📝 *Yangi mijoz so'rovi:*\n";

        $messageTemplate .= "👤 *" . ($language === 'lang_ru' ? 'Имя клиента' : 'Foydalanuvchi') . ":* " . $escapeMarkdown($user->telegram_first_name) . "\n";
        $messageTemplate .= "📛 *" . ($language === 'lang_ru' ? 'Имя пользователя' : 'Username') . ":* @" . $escapeMarkdown($user->telegram_username) . "\n";
        $messageTemplate .= "📱 *" . ($language === 'lang_ru' ? 'Номер телефона' : 'Telefon') . ":* " . $escapeMarkdown($user->phone) . "\n";
        $messageTemplate .= "🛠️ *" . ($language === 'lang_ru' ? 'Услуга' : 'Xizmat turi') . ":* " . $escapeMarkdown($user->service) . "\n";
        $messageTemplate .= "📅 *" . ($language === 'lang_ru' ? 'Дата' : 'Sana') . ":* " . $escapeMarkdown($user->date) . "\n\n";
        $messageTemplate .= "📍 *" . ($language === 'lang_ru' ? 'Адрес местонахождения клиента:' : 'Mizjozning joylashuv manzili:*') . "\n";

        // Telegramga xabar yuborish
        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $messageTemplate,
                'parse_mode' => 'MarkdownV2',
            ]);

            Telegram::sendLocation([
                'chat_id' => $chatId,
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
            ]);
        } catch (\Throwable $e) {
            self::sendMessage(6900325674, $e->getMessage());
            Log::error("Telegram xabar yuborishda xatolik: " . $e->getMessage());
        }
    }

    public static function sendLocationRequest($chatId, $message = null)
    {
        try {
            if (empty(trim($message))) {
                $message = "Iltimos, joylashuvingizni yuboring.";
            }

            $replyKeyboard = [
                [
                    [
                        'text' => '📍 Joylashuvni yuborish',
                        'request_location' => true
                    ]
                ],
                [
                    [
                        'text' => 'Oldingi manzilga',
                    ]
                ]
            ];

            $params = [
                'chat_id' => $chatId,
                'text' => $message,
                'reply_markup' => json_encode([
                    'keyboard' => $replyKeyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
            ];

            Telegram::sendMessage($params);
        } catch (\Throwable $th) {
            Log::error("Telegram joylashuv so'rovida xatolik: " . $th->getMessage());
        }
    }
}
