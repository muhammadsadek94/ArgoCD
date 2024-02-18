<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Jobs\Api\V1\LearnPath\AssignUserToLearnPathByPackageIdJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLearnPathsJob;
use App\Domains\Enterprise\Notifications\LearnpathAssignedToUserNotification;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class AssignUsersFeature extends Feature
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
        $package_subscription =  PackageSubscription::active()->where('id',$request->package_subscription_id)->where(function ($query) use ($admin) {
            $query->whereNull('enterprise_id')
            ->orWhere('enterprise_id', $admin->id)->orWhere('enterprise_id', $admin->enterprise_id);
        })->first();
        if (!$package_subscription) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'learning path',
                    "message" => 'there is no learning paths found'
                ]
            ]);
        }


        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "User",
                    'message' => trans('enterprise::lang.user_not_found')
                ]
            ]);
        }
        $this->run(AssignUserToLearnPathByPackageIdJob::class, [
            'package_id' => $request->package_subscription_id   ,
            'user' => $user
        ]);

        // $user->notify(new LearnpathAssignedToUserNotification($package_subscription));


        return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'user' => $user
                ]
            ]
        );
    }
}
