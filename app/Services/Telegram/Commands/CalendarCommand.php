<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class CalendarCommand
{
    public static function handel($request)
    {
        $message = $request->input('callback_query.data');

        if (preg_match('#date:|next_week#', $message)) {
            return true;
        }
        return false;
    }


    public function execute($request)
    {
        $chatId = $request->input('callback_query.message.chat.id');
        $messageId = $request->input('callback_query.message.message_id');
        $data = $request->input('callback_query.data');

        $language = $request->input('language') ?? 'lang_uz';

        $InlineKeyboard = [];
        $message = '';

        if ($data === 'next_week') {

            $message = 'Vaxtni tanlang:';
            $messageRu = 'Выберите время:';

            if (strval($language) === 'lang_ru') {
                $message = $messageRu;
            }

            $InlineKeyboard = $this->sendCalendar(true);
            TelegramBotHelper::editMessageAndInlineKeyboard($chatId, $messageId, $message, $InlineKeyboard);
            return true;
        }

        // Telefon raqamini so'rash
        $message = 'Tel Raqaminggizni yuboring:';
        $messageRu = 'Отправьте свой номер телефона:';

        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::sendMessage($chatId, $message);
    }


    public static function sendCalendar($currentTimestamp = false)
    {
        $currentTimestamp = $currentTimestamp ? strtotime('next Sunday', strtotime(date('Y-m-d'))) :
            strtotime(date('Y-m-d'));

        // Hozirgi hafta yakshanbasi
        $weekStart = strtotime('sunday this week', $currentTimestamp);
        $weekDays = [];

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("+$i day", $weekStart));

            // Faqat bugungi kundan keyingi kunlarni qo'shish
            if (strtotime($date) >= $currentTimestamp) {
                $weekDays[] = [
                    'text' => date('d', strtotime($date)),
                    'callback_data' => "date:$date",
                ];
            }
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
            'text' => 'Keyingi hafta',
            'callback_data' => "next_week"
        ];

        return [
            'inline_keyboard' => array_chunk($inlineKeyboard, 2) // Tugmalarni 2 ustunli qilish
        ];
    }
}
