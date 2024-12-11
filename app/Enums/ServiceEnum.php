<?php

namespace App\Enums;

use Illuminate\Support\Arr;

class ServiceEnum
{
    const SERVICE_1 = 'Service_1';
    const SERVICE_2 = 'Service_2';
    const SERVICE_3 = 'Service_3';
    const SERVICE_4 = 'Service_4';

    public static function getServicesRu(): array
    {
        return [
            self::SERVICE_1 => 'Генеральная уборка',
            self::SERVICE_2 => 'Уборка после свадьба',
            self::SERVICE_3 => 'Повседневная уборка',
            self::SERVICE_4 => 'Уборка офиса',
        ];
    }

    public static function getServicesUz(): array
    {
        return [
            self::SERVICE_1 => 'Umumiy tozalash',
            self::SERVICE_2 => 'To`ydan keyin tozalash',
            self::SERVICE_3 => 'Kundalik tozalash',
            self::SERVICE_4 => 'Ofis tozalash',
        ];
    }

    public static function getService(string $service, string $lang)
    {
        return ($lang == 'lang_uz') ? Arr::get(self::getServicesUz(), $service, null) : Arr::get(self::getServicesRu(), $service, null);
    }
}
