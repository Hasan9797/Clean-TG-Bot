<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Telegram Request:', $request->all());

        $message = $request->input('message.text');
        $chatId = $request->input('message.chat.id');

        if ($message == '/start') {
            $reply = "Welcome to the Anvar Jigga Clean Service chatbot!.👋\n Choose from categories:";
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $reply,
            ]);
        }
    }
}
