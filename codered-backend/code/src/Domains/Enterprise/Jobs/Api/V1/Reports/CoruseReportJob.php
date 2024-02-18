<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\Reports;

use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use INTCore\OneARTFoundation\Job;
use Illuminate\Http\Request;

class CoruseReportJob extends Job
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
     * @return void
     */
    public function handle( UserEnterpriseRepository $user_repository ,Request $request)
    {
        $admin = auth()->user();
        $users = $user_repository->getEnterpriseUsers($request,$admin->id);
        return $users;
    }
}
