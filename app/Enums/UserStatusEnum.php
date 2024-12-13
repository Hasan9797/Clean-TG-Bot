<?php

namespace App\Enums;

use Illuminate\Support\Arr;

class UserStatusEnum
{
    const PINDING = 1;
    const CREATE = 2;

    public static function getUserStatus(): array
    {
        return [
            self::PINDING => 'В процессе',
            self::CREATE => 'Созданный',
        ];
    }

    public static function getStatus($status)
    {
        return Arr::get(self::getUserStatus(), $status, null);
    }
}
