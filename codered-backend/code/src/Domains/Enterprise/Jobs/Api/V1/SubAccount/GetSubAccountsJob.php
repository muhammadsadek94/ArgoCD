<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\SubAccount;

use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Job;

class GetSubAccountsJob extends Job
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
    public function handle(UserEnterpriseRepository $user_repository, Request $request)
    {
        $admin = auth()->user();
        return $user_repository->getEnterpriseSubAccountWithUsers($request, $admin->id);
    }
}
