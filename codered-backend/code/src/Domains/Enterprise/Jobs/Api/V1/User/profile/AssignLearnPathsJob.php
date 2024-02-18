<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\profile;

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
use INTCore\OneARTFoundation\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class AssignLearnPathsJob extends Job
{
    use Queueable;

    private $learnPaths;
    private $user;
    private $subscribtionStatus;

    /**
     * Create a new job instance.
     *
     * @param $learnPaths
     * @param $user
     */
    public function __construct($user, $learnPaths)
    {
        $this->user = $user;
        $this->learnPaths = EnterpriseLearnPath::whereIn('id', $learnPaths)->get();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->learnPaths as $index => $learnPath) {
            // assign user to learnPaths
            $user_subscription = UserSubscription::firstOrCreate([
                'package_id' => $learnPath->package_id,
                'user_id' => $this->user->id,
            ]);
            $package_subscription = PackageSubscription::findOrFail($learnPath->package_id);
            if ($user_subscription->wasRecentlyCreated) {

                $user_subscription->status = 1;
                $user_subscription->expired_at = Carbon::now()->addDays(365);


                $user_subscription->save();
            }


        }
    }
}
