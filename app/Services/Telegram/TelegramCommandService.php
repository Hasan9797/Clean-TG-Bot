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


    const CALLBACK_CLASS = [
        LanguageCommand::class,
        ServiceCommand::class,
        CalendarCommand::class,
    ];

    const CONTACT_CLASS = [
        ContactCommand::class
    ];

    const MESSAGE_CLASS = [
        StartCommand::class,
        ServicesCommand::class,
    ];

    public function getClass($request)
    {
        $data = $request->input('callback_query.data') ?? false;
        $contact = $request->input('message.contact');
        $commandsClass = self::MESSAGE_CLASS;

        if ($data) {
            $commandsClass = self::CALLBACK_CLASS;
        } else if ($contact){
            $commandsClass = self::CONTACT_CLASS;
        }

        foreach ($commandsClass as $commandClass) {
            if ($commandClass::handel($request) === true) {
                return (new $commandClass());
            }
        }
    }
}
