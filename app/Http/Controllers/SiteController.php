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

    public function index(Request $request)
    {
        $users = User::paginate(20);
        return view('components.site.index', ['users' => $users]);
    }
}
