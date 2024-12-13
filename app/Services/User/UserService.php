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

    public static function clientCreateAndUpdate($chatId, array $change)
{
    try {
        $user = self::getByChatId($chatId);

        if (!$user || (intval($user->status) === UserStatusEnum::CREATE)) {
            return User::create([
                'chat_id' => $chatId,
                'telegram_first_name' => $change['telegram_first_name'] ?? null,
                'telegram_username' => $change['telegram_username'] ?? null,
                'status' => $change['status'] ?? UserStatusEnum::PINDING,
                'phone' => $change['phone'] ?? null,
            ]);
            Log::info("User yaratish muvaffaqiyatli: ", $user->toArray());
        }

        $user->update($change);
        Log::info('User Update:', $user->toArray());
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
        $user =  User::select('latitude', 'longitude')
            ->where('chat_id', intval($chatId))
            ->first();

        return $user ? $user->toArray() : [];
    }
}
