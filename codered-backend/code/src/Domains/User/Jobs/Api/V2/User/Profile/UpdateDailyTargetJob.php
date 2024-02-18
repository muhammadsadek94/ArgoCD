<?php

namespace App\Domains\User\Jobs\Api\V2\User\Profile;

use App\Domains\Course\Models\CourseEnrollment;
use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateDailyTargetJob extends Job
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CourseEnrollment::whereNotNull('weekly_target')
        ->orWhereNotNull('weekly_target')
        ->orWhereNotNull('week_start_date')
        ->orWhereNotNull('week_end_date')
        ->update([
            'weekly_target' => 0,
            'selected_days' => null,
            'week_start_date' =>  null,
            'week_end_date' => null,
        ]);
    }
}
