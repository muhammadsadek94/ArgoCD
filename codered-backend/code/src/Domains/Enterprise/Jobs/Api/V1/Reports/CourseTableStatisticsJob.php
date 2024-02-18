<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\Reports;

use App\Domains\Enterprise\Repositories\CourseEnterpriseRepository;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use INTCore\OneARTFoundation\Job;
use Illuminate\Http\Request;

class CourseTableStatisticsJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     */
    public function handle(UserEnterpriseRepository $user_repository,CourseEnterpriseRepository $courseEnterpriseRepository , Request $request)
    {
        $admin = auth()->user();
        $user_query = $user_repository->getUsersQuery($request, $admin->id);
        $user_ids = $user_query->pluck('id');

        $total_watched_min = $courseEnterpriseRepository->getEnterpriseTotalMinsWatched($request, $admin->id);
        $total_enrolment = $user_repository->getEnterpriseTotalEnrollmentForSubAccounts($request, $user_ids);
        $total_completed = $user_repository->getEnterpriseTotalCompleted($request, $user_ids);
        $average_enrolment = $courseEnterpriseRepository->getEnterpriseAverageEnrollment($request, $admin->id);
        $average_completion_rate = $user_repository->getEnterpriseAverageCompletionRate($request, $user_ids);
        $average_rate = $user_repository->getEnterpriseAverageRate($request, $user_ids);
        $average_score = $user_repository->getEnterpriseAverageScore($request, $user_ids);
        return [
            'total_watched_min' => number_format((float)$total_watched_min, 2, '.', '') ,
            'total_completed' => $total_completed ?  $total_completed  : 0,
            'total_enrolment' => $total_enrolment ?  $total_enrolment : 0,
            'average_enrolment' => number_format((float)$average_enrolment, 2, '.', ''),
            'average_completion_rate' => number_format((float)$average_completion_rate, 2, '.', ''),
            'average_score' => $average_score ?  number_format((float)$average_score, 2, '.', '') : 0,
            'average_rate' => $average_rate ?  number_format((float)$average_rate, 2, '.', '') : 0,
        ];
    }
}
