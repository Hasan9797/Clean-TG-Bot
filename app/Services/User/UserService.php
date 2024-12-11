<?php

namespace App\Services\User;

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
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 20;

        return $this->userRepository->index($limit);
    }

    public function store($request)
    {
        return $this->userRepository->store($request->all());
    }

    public function findOne($id)
    {
        return $this->userRepository->getUserById($id);
    }

    public function update($data, $id)
    {
        return $this->userRepository->update($data, $id);
    }

    public function delete($data)
    {
        //
    }

}
