<?php


namespace App\Domains\Course\Enum;


use App\Foundation\BasicEnum;

class LessonType extends BasicEnum
{
    const VIDEO = 1;
    const DOCUMENT = 2;
    const QUIZ = 3;
    const LAB = 4;
    const CYPER_Q = 5;
    const VOUCHER = 6;
    const PROJECT = 7;
    const VITAL_SOURCE = 8;
    const CHECKPOINT = 9;
}
