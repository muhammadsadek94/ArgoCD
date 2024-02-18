<?php


namespace App\Domains\Course\Enum;

use App\Foundation\BasicEnum;

class CourseCategoryColors extends BasicEnum
{

    const PURPLE = '#920EF6';
    const RED = '#DF0E2A';
    const YELLOW = '#F6D70E';
    const BLUE = '#4592FF';
    const FUCHIA = '#C52C62';
    const MUSTARD = '#FFA34D';

    public static function getList()
    {
        return [
            self::PURPLE      => 'Purple',
            self::RED      => 'Red',
            self::YELLOW      => 'Yellow',
            self::FUCHIA      => 'Fuchsia',
            self::MUSTARD      => 'Mustard',
        ];
    }

}

