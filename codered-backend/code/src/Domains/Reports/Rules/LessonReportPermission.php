<?php

namespace App\Domains\Reports\Rules;

use App\Foundation\BasicEnum;

class LessonReportPermission extends BasicEnum
{
    const MODULE = 'Lesson Report';

    const LESSON_REPORT_INDEX = [
        "name"    => "Lesson Reports list",
        "ability" => "lesson_report.index"
    ];

   

}
