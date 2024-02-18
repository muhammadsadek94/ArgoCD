<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\SubAccount;

use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use INTCore\OneARTFoundation\Job;

class GetSubAccountByIdJob extends Job
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserEnterpriseRepository $user_enterprise_repository)
    {
        $admin = auth()->user();
        $user = $user_enterprise_repository->getEnterpriseUserById($admin->id, $this->request->subAccount);
        return $user;
    }

    /**
     * Create a new job instance.
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;

    }
}
