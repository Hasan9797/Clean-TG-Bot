<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
        // $users = $this->userService->index($request);

        return view('components.dashboard');
    }

    public function users(Request $request)
    {
        return view('components.users');
    }

    public function store(Request $request)
    {
        $this->userService->store($request);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(Request $request)
    {
        $user = $this->userService->findOne($request);

        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function idet(Request $request, $id)
    {
        $user = $this->userService->findOne($request, $id);
        return view('users.idet', $user);
    }

    public function update(Request $request, $id)
    {
        $this->userService->update($request->all(), $id);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function delete(Request $request)
    {
        $this->userService->delete($request);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
