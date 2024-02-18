<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\Reports;

use App\Domains\Enterprise\Repositories\CourseEnterpriseRepository;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use INTCore\OneARTFoundation\Job;
use Illuminate\Http\Request;

class CourseReportJob extends Job
{
    private $sortBy;
    private $table;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request ,$sortBy= 'sum( watch_history_times.watched_time)' , $table = 'watch_history_times')
    {
        $this->sortBy = $sortBy;
        $this->table = $table;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle( CourseEnterpriseRepository $course_repository ,Request $request)
    {
        $admin = auth()->user();
        $courses = $course_repository->getEnterpriseFilteredCourses($request,$admin->id ,$this->table ,$this->sortBy);
        return $courses;
    }
}
