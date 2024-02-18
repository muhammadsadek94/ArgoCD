<?php

namespace App\Domains\Reports\Rules;

use App\Foundation\BasicEnum;

class SummaryReportPermission extends BasicEnum
{
    const MODULE = 'Summary Report Permission';

    const SUMMARY_REPORT_INDEX = [
        "name"    => "Summary Reports list",
        "ability" => "summary-report.index"
    ];

   

}
