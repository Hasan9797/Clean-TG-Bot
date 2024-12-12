<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($request)
    {
        $limit = $request->get('limit') ?? 10;

        return $this->userRepository->index($limit);
    }

    public function admins($request)
    {
        $limit = $request->get('limit') ?? 20;

        return $this->userRepository->admin($limit);
    }

    public function store($data)
    {
        return $this->userRepository->store($data);
    }

    public function findOne($id)
    {
        return $this->userRepository->getUserById($id);
    }

    public function update($data, $id)
    {
        return $this->userRepository->update($data, $id);
    }

    public function clientCount()
    {
        return $this->userRepository->clientsCount();
    }

    public function clientCreateAndUpdate($chatId, $change)
    {
        User::find('chat_id', $chatId)->update($change);
        return true;
    }
}
