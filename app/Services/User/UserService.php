<?php

namespace App\Services\User;

use App\Enums\UserStatusEnum;
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

    public static function getByChatId($chatId)
    {
        return User::where('chat_id', $chatId)->first();
    }

    public static function clientCreateAndUpdate($chatId, array $change)
    {
        try {
            $user = self::getByChatId($chatId);

            if (!$user || $user->status === UserStatusEnum::CREATE) {
                $user = User::create($change);
                return $user;
            }

            $user->update($change);
            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getLocationByChatId($chatId)
    {
        $user =  User::select('latitude', 'longitude')
            ->where('chat_id', $chatId)
            ->first();

        return $user ? $user->toArray() : [];
    }
}
