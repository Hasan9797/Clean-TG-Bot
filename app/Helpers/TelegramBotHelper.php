<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotHelper
{

    public static function inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard)
    {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => json_encode($inlineKeyboard)
        ]);
    }

    public static function editMessageAndInlineKeyboard($chatId, $messageId, $message, $inlineKeyboard)
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
        try {
            Telegram::deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram deleteMessage error:', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
