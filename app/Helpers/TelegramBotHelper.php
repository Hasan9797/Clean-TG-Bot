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

    public static function sendMessage($chatId, $message, $parseMode = 'HTML')
    {
        try {
            return Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
            ]);
        } catch (\Throwable $th) {
            Log::error("Telegramga xabar yuborishda xatolik: " . $th->getMessage(), [
                'chat_id' => $chatId,
                'message' => $message,
            ]);

            Telegram::sendMessage([
                'chat_id' => 6900325674,
                'text' => "Xatolik: " . $th->getMessage(),
            ]);
        }
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
        $escapeMarkdown = function ($text) {
            $search = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
            $replace = array_map(fn($char) => '\\' . $char, $search);
            return str_replace($search, $replace, $text);
        };

        $messageTemplate = $language === 'lang_ru'
            ? "📝 *Новый клиент запросил:*\n"
            : "📝 *Yangi mijoz so'rovi:*\n";

        $messageTemplate .= "👤 *" . ($language === 'lang_ru' ? 'Имя клиента' : 'Foydalanuvchi') . ":* " . $escapeMarkdown($user->telegram_first_name ?? 'Noma\'lum') . "\n";
        $messageTemplate .= "📛 *" . ($language === 'lang_ru' ? 'Имя пользователя' : 'Username') . ":* @" . $escapeMarkdown($user->telegram_username ?? 'Noma\'lum') . "\n";
        $messageTemplate .= "📱 *" . ($language === 'lang_ru' ? 'Номер телефона' : 'Telefon') . ":* " . $escapeMarkdown($user->phone ?? 'Noma\'lum') . "\n";
        $messageTemplate .= "🛠️ *" . ($language === 'lang_ru' ? 'Услуга' : 'Xizmat turi') . ":* " . $escapeMarkdown($user->service ?? 'Noma\'lum') . "\n";
        $messageTemplate .= "📅 *" . ($language === 'lang_ru' ? 'Дата' : 'Sana') . ":* " . $escapeMarkdown($user->date ?? 'Noma\'lum') . "\n\n";
        $messageTemplate .= "📍 *" . ($language === 'lang_ru' ? 'Адрес местонахождения клиента:*' : 'Mijozning joylashuv manzili:*') . "\n";

        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $messageTemplate,
                'parse_mode' => 'MarkdownV2',
            ]);

            if (isset($user->latitude, $user->longitude)) {
                Telegram::sendLocation([
                    'chat_id' => $chatId,
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Telegram xabar yuborishda xatolik: " . $e->getMessage(), [
                'chat_id' => $chatId,
                'user' => $user,
            ]);
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

    private static function escapeMarkdownV2($text)
    {
        $search = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        $replace = array_map(fn($char) => '\\' . $char, $search);

        return str_replace($search, $replace, $text);
    }
}
