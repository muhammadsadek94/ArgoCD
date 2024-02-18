<?php

namespace App\Domains\Payments\Enum;

use App\Foundation\BasicEnum;

class LearnPathType extends BasicEnum
{
    const CAREER = 1;
    const SKILL = 2;
    const CERTIFICATE = 3;
    const BUNDLE_COURSES = 4;
    const BUNDLE_CATEGORY = 5;

    public static function getTypeName($type) {
        $types = [
            self::CAREER => [
                "name" => "Career Path"
            ],
            self::SKILL => [
                "name" => "Skill Path"
            ],
            self::CERTIFICATE => [
                "name" => "Certification Path"
            ],
            self::BUNDLE_CATEGORY => [
                "name" => "Custom Bundle"
            ],
            self::BUNDLE_COURSES => [
                "name" => "Custom Bundle"
            ]
        ];


        return  @$types[$type]['name'];
    }

}
