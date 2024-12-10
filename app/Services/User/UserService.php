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
        $page = $request->get('page');
        $limit = $request->get('limit');

        return $this->userRepository->index($page, $limit);
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
