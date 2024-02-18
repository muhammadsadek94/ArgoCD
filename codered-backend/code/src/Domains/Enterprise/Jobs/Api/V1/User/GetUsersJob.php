<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Job;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;

class GetUsersJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserEnterpriseRepository $user_repository ,Request $request)
    {
        $admin = auth()->user();
        return $user_repository->getEnterpriseUsers($request,$admin->id);
    }
}
