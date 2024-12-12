<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Commands\CalendarCommand;
use App\Services\Telegram\Commands\ContactCommand;
use App\Services\Telegram\Commands\LanguageCommand;
use App\Services\Telegram\Commands\ServiceCommand;
use App\Services\Telegram\Commands\ServicesCommand;
use App\Services\Telegram\Commands\StartCommand;

class TelegramCommandService
{

    const GROUP_CHAT_ID = -4711715104;

    const CALLBACK_CLASS = [
        LanguageCommand::class,
        ServiceCommand::class,
        CalendarCommand::class,
    ];

    const MESSAGE_CLASS = [
        StartCommand::class,
        ServicesCommand::class,
        ContactCommand::class
    ];

    public function getClass($request)
    {
        $data = $request->input('callback_query.data') ?? false;
        $chatId = $request->input('message.chat_id') ?? $request->input('callback_query.message.chat_id');

        if($chatId == self::GROUP_CHAT_ID){
            return false;
        }

        $commandsClass = self::MESSAGE_CLASS;

        if ($data) {
            $commandsClass = self::CALLBACK_CLASS;
        }

        foreach ($commandsClass as $commandClass) {
            if ($commandClass::handel($request) === true) {
                return (new $commandClass());
            }
        }
    }
}
