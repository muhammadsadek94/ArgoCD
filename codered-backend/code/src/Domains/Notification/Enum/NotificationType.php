<?php


namespace App\Domains\Notification\Enum;


use App\Foundation\BasicEnum;
use App\Domains\Property\Models\Property;
use Illuminate\Database\Eloquent\Relations\Relation;

class NotificationType extends BasicEnum
{
    const ADMIN = 'admin';

    public static function getMorphs()
    {
        return [
            //            self::PROPERTY => Property::class,
        ];
    }

    public static function buildMorph()
    {
        Relation::morphMap(self::getMorphs());
    }

}
