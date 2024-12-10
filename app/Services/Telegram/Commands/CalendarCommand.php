<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Illuminate\Support\Facades\Cache;

class CalendarCommand
{
    public static function handel($request)
    {
        $message = $request->input('callback_query.data');

        if (preg_match('#date:|next_week_#', $message)) {
            return true;
        }
        return false;
    }


    public function execute($request)
    {
        $chatId = $request->input('callback_query.message.chat.id');
        $messageId = $request->input('callback_query.message.message_id');
        $data = $request->input('callback_query.data');

        $language = Cache::get("language_$chatId", 'lang_uz');

        $InlineKeyboard = [];

        if (preg_match('/next_week_/', $data)) {
            $message = 'Vaxtni tanlang:';
            $messageRu = 'Выберите время:';

            if (strval($language) === 'lang_ru') {
                $message = $messageRu;
            }

            $dataParts = explode('_', $data);
            if (isset($dataParts[2])) {
                $currentTimeStump = intval($dataParts[2]);
            } else {
                // Xatolik qaytarish
                $message = 'Xatolik yuz berdi. Iltimos, qayta urunib ko\'ring.';
                TelegramBotHelper::sendMessage($chatId, $message);
                return false;
            }

            $InlineKeyboard = $this->sendCalendar($currentTimeStump);
            TelegramBotHelper::editMessageAndInlineKeyboard($chatId, $messageId, $message, $InlineKeyboard);
            return true;
        }

        $message = 'So\'rovinggiz qabul qilindi tez orada sizga operator aloqaga chiqadi:';
        $messageRu = 'Ваш запрос принят, оператор свяжется с вами в ближайшее время:';

        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::sendMessage($chatId, $messageId);
    }


    public static function sendCalendar($currentTimestamp = null)
    {
        // Hozirgi vaqtdan foydalanish
        $currentTimestamp = $currentTimestamp ?: strtotime(date('Y-m-d'));

        // Hozirgi hafta dushanbasi
        $weekStart = strtotime('monday this week', $currentTimestamp);  // Haftaning boshlanishini aniqlash
        $weekDays = [];

        // Haftadagi har bir kunni olish
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

        // Inline keyboard uchun boshlang'ich tugmalar
        $inlineKeyboard = [
            [
                'text' => $monthName,
                'callback_data' => 'ignore'
            ]
        ];

        // Haftaning kunlarini inline tugmalarga qo'shish
        foreach ($weekDays as $day) {
            $inlineKeyboard[] = [
                'text' => $day['text'],
                'callback_data' => $day['callback_data']
            ];
        }

        // Keyingi hafta tugmasi (dushanbadan boshlash)
        $nextWeekStart = strtotime('next monday', $currentTimestamp);  // Keyingi hafta dushanbasi

        $inlineKeyboard[] = [
            'text' => 'Keyingi hafta',
            'callback_data' => "next_week_$nextWeekStart"
        ];

        // Tugmalarni 2 ustunli qilish
        return [
            'inline_keyboard' => array_chunk($inlineKeyboard, 2)
        ];
    }
}
