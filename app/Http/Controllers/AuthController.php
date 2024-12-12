<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function loginForm(Request $request)
    {
        return redirect('/');
    }

    public function logout(Request $request)
    {
        return redirect('login');
    }
}
