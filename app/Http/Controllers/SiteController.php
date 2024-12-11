<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function login(Request $request)
    {
        return view('login');
    }

    public function loginForm(Request $request)
    {
        return redirect('/');
    }

    public function logout(Request $request)
    {
        return redirect('login');
    }

    public function home(Request $request)
    {
        $users = User::paginate(20);
        return view('components.dashboard', ['users' => $users]);
    }


    public function users(Request $request)
    {
        $users = $this->userService->index($request); // paginate $users
        return view('components.users', ['users' => $users]);
    }
}
