<?php


namespace App\Domains\Course\Enum;


class CourseActivationStatus extends \App\Foundation\BasicEnum
{
    const DEACTIVATED = 0;
    const ACTIVE = 1;
    const DRAFT = 2;
    const PENDING_APPROVAL = 3;
    const HIDDEN = 4;


    public static function getActivationList()
    {
        return [
            self::ACTIVE           => 'Published',
            self::DEACTIVATED      => 'Unpublished',
            self::DRAFT            => 'Draft',
            self::PENDING_APPROVAL => 'Pending Approval',
            self::HIDDEN => 'Hidden',
        ];
    }

}
