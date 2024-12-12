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
        $users = $this->userService->index($request);
        $admin = $this->userService->admins($request);
        $count = $this->userService->clientCount();
        return view('components.site.index', ['users' => $users, 'count' => $count, 'admin' => $admin]);
    }
}
