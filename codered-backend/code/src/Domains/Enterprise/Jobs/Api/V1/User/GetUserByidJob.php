<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User;

use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserType;
use Illuminate\Support\Facades\Auth;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Repositories\UserRepository;

class GetUserByidJob extends Job
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserEnterpriseRepository $user_enterprise_repository)
    {
        $admin = auth()->user();
        $user = $user_enterprise_repository->getEnterpriseUserById($admin->id, $this->request->user);
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
