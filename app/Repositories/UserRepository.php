<?php

namespace App\Repositories;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;

class UserRepository
{
    public function index($limit)
    {
        return User::where('status', UserStatusEnum::CREATE)->paginate($limit);
    }

    public function admin($limit = 15)
    {
        return User::where('role', UserRoleEnum::USER_ADMIN)->paginate($limit);
    }

    public function store($data)
    {
        return User::create($data);
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function update(array $data, int $id)
    {
        return User::where('id', $id)->update($data);
    }

    public function clientsCount()
    {
        return User::where('role', UserRoleEnum::USER_CLIENT)->count();
    }
}
