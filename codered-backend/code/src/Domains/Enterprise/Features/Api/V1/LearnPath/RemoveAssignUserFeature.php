<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\Enterprise\Jobs\Api\V1\LearnPath\AssignUserToLearnPathByPackageIdJob;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Jobs\Api\V1\User\Profile\DeleteSubscriptionJob;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class RemoveAssignUserFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(Request $request)
    {

        $admin = auth()->user();
        $package_subscription = PackageSubscription::active()->where('id',$request->id)->where(function ($query) use ($admin) {
            $query->whereNull('enterprise_id')
                ->orWhere('enterprise_id', $admin->id)
                ->orWhere('enterprise_id', $admin->enterprise_id);
            ;
        })->first();
        if (!$package_subscription) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'learning path',
                    "message" => 'there is no learning paths found'
                ]
            ]);
        }

        $status = $this->run(DeleteSubscriptionJob::class, [
            'request'       => $request,
        ]);



        return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'user_id' => $request->user_id
                ]
            ]
        );
    }
}
