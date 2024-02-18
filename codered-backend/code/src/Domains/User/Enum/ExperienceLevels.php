<?php


namespace App\Domains\User\Enum;


use App\Foundation\BasicEnum;

class ExperienceLevels extends BasicEnum
{
    const BEGINNER = 1;
    const INTERMEDIATE = 2;
    const ADVANCED = 3;

    public static function getLevel($key) {
        $levels = [
            self::BEGINNER => [
                'name' => 'Beginner'
            ],
            self::INTERMEDIATE => [
                'name' => 'Intermediate'
            ],
            self::ADVANCED => [
                'name' => 'Advanced'
            ]
        ];

        return $levels[$key]['name'];
    }
}
