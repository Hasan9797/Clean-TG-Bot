<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Telegram\Bot\Keyboard\Keyboard;

class CalendarCommand
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

        $keyboard = $this->sendCalendar();

        $message = $messageUz;


        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $keyboard);
    }


    public function sendCalendar()
    {
        // Agar sana ko'rsatilmagan bo'lsa, hozirgi sanani olish
        $currentDate = date('Y-m-d');
        $currentTimestamp = strtotime($currentDate);

        // Hafta kunlari va oyning bosh sanasini aniqlash
        $weekStart = strtotime('last Sunday', $currentTimestamp); // Haftaning boshlanishi
        $weekDays = [];

        for ($i = 0; $i < 7; $i++) { // Haftaning har bir kuni
            $date = date('Y-m-d', strtotime("+$i day", $weekStart));
            $weekDays[] = [
                'text' => date('d', strtotime($date)), // Tugmada faqat kun
                'callback_data' => "date:$date",      // callback_data uchun to'liq sana
            ];
        }

        // Oyning nomini olish
        $monthName = date('F Y', $currentTimestamp);

        // Inline keyboard yaratish
        $keyboard = Keyboard::make()->inline()
            ->row(
                Keyboard::inlineButton(['text' => $monthName, 'callback_data' => 'ignore']) // Oyni ko'rsatish (bosilmaydi)
            )
            ->row(array_map(fn($day) => Keyboard::inlineButton($day), $weekDays)) // Haftaning kunlari
            ->row([
                Keyboard::inlineButton(['text' => '<< Oldingi hafta', 'callback_data' => 'prev_week']),
                Keyboard::inlineButton(['text' => 'Keyingi hafta >>', 'callback_data' => 'next_week'])
            ]);

        return $keyboard;
    }
}
