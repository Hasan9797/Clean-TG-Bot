<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    public function handle(Request $request){
        return response($request->get('message'));
    }
}
