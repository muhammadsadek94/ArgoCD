<?php

namespace App\Domains\Reports\Rules;

use App\Foundation\BasicEnum;

class GlobalKnowledgeReportPermission extends BasicEnum
{
    const MODULE = 'Global Knowledge Report';

    const KNOWLEDGE_REPORT_INDEX = [
        "name"    => "Global Knowledge Reports list",
        "ability" => "global_knowledge_report.index"
    ];

   

}
