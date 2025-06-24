<?php

namespace App\Enum;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case Other = 'other';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
