<?php


namespace App\Domains\Course\Enum;


use App\Foundation\BasicEnum;

class ProjectApplicationStatus extends BasicEnum
{
   const SUBMITTED = 0;
   const UNDER_REVIEW = 1;
   const REVIEW_COMPLETED = 2;

}
