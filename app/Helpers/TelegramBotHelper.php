<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotHelper
{

    public static function inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard = [])
    {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => json_encode($inlineKeyboard)
        ]);
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
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }

    public static function deleteMessage($chatId, $messageId)
    {
        Telegram::deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);
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
                        'text' => 'Kontaktni yuborish',
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

            Telegram::sendMessage($params);  // Simplified API call
        } catch (\Throwable $th) {
            self::sendMessage($chatId, $th->getMessage());
        }
    }
}
