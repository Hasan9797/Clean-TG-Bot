<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Commands\CalendarCommand;
use App\Services\Telegram\Commands\ContactCommand;
use App\Services\Telegram\Commands\LanguageCommand;
use App\Services\Telegram\Commands\LocationCommand;
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
        ContactCommand::class,
        LocationCommand::class
    ];

    public function getClass($request)
    {
        $data = $request->input('callback_query.data') ?? false;
        $chatType = $request->input('message.chat.type');

        if ($chatType === 'group' || $chatType === 'supergroup') {
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
