<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Commands\CategoriesCommand;
use App\Services\Telegram\Commands\StartCommand;

class TelegramCommandService
{


    const CALLBACK_CLASS = [
        //
    ];
    const MESSAGE_CLASS = [
        StartCommand::class,
        CategoriesCommand::class,
    ];

    public function getClass($request)
    {
        $data = $request->input('callback_query.data') ?? false;
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
