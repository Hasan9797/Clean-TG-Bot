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
        if (empty($message)) {
            $message = "Iltimos, telefon raqamingizni yuboring.";
        }

        // Define the keyboard with a button that requests the contact
        $replyKeyboard = [
            [
                [
                    'text' => 'Kontaktni yuborish',  // Button text
                    'request_contact' => true        // This enables the phone number sending feature
                ]
            ]
        ];

        // Parameters for the request to send the message and keyboard
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => json_encode([
                'keyboard' => $replyKeyboard,
                'resize_keyboard' => true, // Make the keyboard resizable
                'one_time_keyboard' => true, // Hide the keyboard after use
            ])
        ];

        // Send the request to Telegram's API to send the message with the keyboard
        Telegram::sendRequest('POST', 'sendMessage', $params);
    }
}
