<?php

namespace App\Domains\Partner\Repositories;

use App\Domains\Partner\Enum\ExperienceLevels;
use Illuminate\Support\Collection;

class ExperienceLevelRepository
{
    private static $levels = [
        [
            'id'    => ExperienceLevels::BEGINNER,
            'name'  => 'Beginner',
            'image' => ''
        ],
        [
            'id'    => ExperienceLevels::INTERMEDIATE,
            'name'  => 'Intermediate',
            'image' => ''
        ],
        [
            'id'    => ExperienceLevels::ADVANCED,
            'name'  => 'Advanced',
            'image' => ''
        ]
    ];

    public static function getLevels()
    {
        $data = self::$levels;

        return (new Collection($data))->sortBy('id');
    }

    public static function getPluckLevels() {
        return self::getLevels()->pluck('name', 'id');
    }

}
