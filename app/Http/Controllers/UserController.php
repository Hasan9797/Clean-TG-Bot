<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->admins($request);

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create(Request $request)
    {
        return view('users.create');
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
        $user = $this->userService->findOne($id);
        return view('users.idet', $user);
    }

    public function update(Request $request, $id)
    {
        $this->userService->update($request->all(), $id);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function delete(Request $request)
    {
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
