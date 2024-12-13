<?php

namespace App\Enums;

use Illuminate\Support\Arr;

class UserRoleEnum
{
    const USER_CLIENT = 1;
    const USER_ADMIN = 2;

    public static function getUserRoles(): array
    {
        return [
            self::USER_ADMIN => 'Admin',
            self::USER_CLIENT => 'Client',
        ];
    }

    public static function getUserRole($role)
    {
        return Arr::get(self::getUserRoles(), $role, null);
    }
}
