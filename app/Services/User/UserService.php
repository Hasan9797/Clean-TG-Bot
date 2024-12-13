<?php

namespace App\Services\User;

use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

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
        return User::where('chat_id', intval($chatId))->first();
    }

    public static function clientCreateOrUpdate($chatId, array $change)
    {
        try {
            $user = User::where('chat_id', $chatId)->first();

            if ($user && $user->status === UserStatusEnum::CREATE) {
                return User::create($change);
            }

            if ($user && $user->status === UserStatusEnum::PENDING) {
                $user->update($change);
                return $user;
            }

            if (!$user) {
                return User::create($change);
            }

            return $user;
        } catch (\Throwable $th) {
            Log::error("User yaratish yoki yangilashda xatolik: " . $th->getMessage(), [
                'chat_id' => $chatId,
                'changes' => $change,
            ]);
            throw $th;
        }
    }




    public static function getLocationByChatId($chatId)
    {
        $user = User::select('latitude', 'longitude')
            ->where('chat_id', intval($chatId))
            ->first();

        return $user ? $user->toArray() : null;
    }
}
