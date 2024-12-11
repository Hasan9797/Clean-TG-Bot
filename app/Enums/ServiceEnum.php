<?php

namespace App\Enums;

use Illuminate\Support\Arr;

class ServiceEnum
{
    const SERVICE_1 = 'service_1';
    const SERVICE_2 = 'service_2';
    const SERVICE_3 = 'service_3';
    const SERVICE_4 = 'service_4';

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

    public static function getService(string $service, string $lang): string
    {
        if ($lang === 'lang_uz') {
            return Arr::get(self::getServicesUz(), $service, null);
        }
        return  Arr::get(self::getServicesRu(), $service, null);
    }
}
