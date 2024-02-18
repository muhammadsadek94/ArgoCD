<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\LearnPath;

use App\Domains\Enterprise\Enum\LearnPathsDeadlineType;
use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\Enterprise\Models\License;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use INTCore\OneARTFoundation\Job;

class AssignUserToLearnPathByPackageIdJob extends Job
{
// TODO: delete file
    use Queueable;

    private $package_id;
    private $user;
    private $subscribtionStatus;

    /**
     * Create a new job instance.
     *
     * @param $package_id
     * @param $user
     */
    public function __construct($user, $package_id)
    {
        $this->user = $user;
        $this->package_id = $package_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // assign user to learnPaths
        $user_subscription = UserSubscription::firstOrCreate([
            'package_id' => $this->package_id,
            'user_id' => $this->user->id,
        ]);

        $package_subscription = PackageSubscription::findOrFail($this->package_id);
        if ($user_subscription->wasRecentlyCreated) {

            $user_subscription->status = 1;
            // if ($package_subscription['deadline-type'] == LearnPathsDeadlineType::STATIC)
            //     $user_subscription->expired_at = $package_subscription->expiration_date;
            // else
            //     $user_subscription->expired_at = Carbon::now()->addDays($package_subscription->expiration_days);
            $user_subscription->expired_at = Carbon::now()->addDays(365);
            $user_subscription->save();
        }


    }
}
