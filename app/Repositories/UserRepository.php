<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function index($page, $limit)
    {
        return User::paginate($limit);
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

    public function delete($data)
    {
        return User::destroy($data);
    }
}
