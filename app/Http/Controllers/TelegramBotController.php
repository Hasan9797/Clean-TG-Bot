<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function handle(Request $request)
    {
        $message = $request->get('message');
        $chatId = $request->get('chat_id');
        dd($request);
        if ($message == '/start') {
            $reply = "Welcome to the Anvar Jigga Clean Service chatbot!.👋";
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $reply,
            ]);
        }
    }
}
