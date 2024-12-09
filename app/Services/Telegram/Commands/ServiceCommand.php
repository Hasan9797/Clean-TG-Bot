<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Telegram\Bot\Keyboard\Keyboard;

class ServiceCommand
{
    public static function handel($request)
    {
        $message = explode('_', strval($request->input('callback_query.data')));

        if (count($message) === 2 && strval($message[0]) === 'service') {
            return true;
        }
        return false;
    }


    public function execute($request)
    {
        $chatId = $request->input('callback_query.message.chat.id');
        $messageId = $request->input('callback_query.message.message_id');
        $language = $request->input('language') ?? 'lang_uz';

        $messageUz = 'Vaxtni tanlang:';
        $messageRu = 'Выберите время:';

        // Sanalar uchun inline keyboard

        $inlineKeyboard = $this->sendCalendar();

        $message = $messageUz;


        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }


    public function sendCalendar($currentDate = null)
    {
        $currentDate = $currentDate ?: date('Y-m-d');
        $currentTimestamp = strtotime($currentDate);

        $weekStart = strtotime('last Sunday', $currentTimestamp);
        $weekDays = [];

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("+$i day", $weekStart));
            $weekDays[] = [
                'text' => date('d', strtotime($date)),
                'callback_data' => "date:$date",
            ];
        }

        $monthName = date('F Y', $currentTimestamp);

        $inlineKeyboard = [
            [
                'text' => $monthName,
                'callback_data' => 'ignore'
            ]
        ];

        foreach ($weekDays as $day) {
            $inlineKeyboard[] = [
                'text' => $day['text'],
                'callback_data' => $day['callback_data']
            ];
        }

        $inlineKeyboard[] = [
            'text' => '<< Oldingi hafta',
            'callback_data' => 'prev_week'
        ];
        $inlineKeyboard[] = [
            'text' => 'Keyingi hafta >>',
            'callback_data' => 'next_week'
        ];

        return [
            'inline_keyboard' => array_chunk($inlineKeyboard, 2) // Tugmalarni 2 ustunli qilish
        ];
    }
}
