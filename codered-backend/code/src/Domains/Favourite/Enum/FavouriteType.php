<?php

namespace App\Domains\Favourite\Enum;

use App\Foundation\BasicEnum;
use App\Domains\User\Models\User;
use App\Domains\Course\Models\Lesson;
use Illuminate\Database\Eloquent\Relations\Relation;

class FavouriteType extends BasicEnum {

    const USER = 'user';
    const LESSON = 'lesson';

    public static function getMorphs() {
        return [
            self::USER => User::class,
            self::LESSON => Lesson::class,
        ];
    }

    public static function buildMorph() {
        Relation::morphMap(self::getMorphs());
    }
}
