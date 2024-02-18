<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\profile;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Enum\UserType;
use Illuminate\Support\Facades\Auth;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Repositories\UserRepository;

class AssignToSubaccount extends Job
{

    private $subAccount_id;
    private $user_id;

    /**
     * Create a new job instance.
     *
     * @param $user_id
     * @param $subAccount_id
     */
    public function __construct($user_id, $subAccount_id)
    {
        $this->user_id = $user_id;
        $this->subAccount_id = $subAccount_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
     User::where('id', '=', $this->user_id)->limit(1)->update(['subaccount_id'=>$this->subAccount_id]);
    }
}
