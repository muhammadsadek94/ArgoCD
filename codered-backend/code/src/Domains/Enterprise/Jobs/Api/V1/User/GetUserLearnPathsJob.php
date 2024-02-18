<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User;

use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserType;
use Illuminate\Support\Facades\Auth;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Repositories\UserRepository;

class GetUserLearnPathsJob extends Job
{

    private $user;
    /**
     * Create a new job instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user  = $user;

    }

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
}
