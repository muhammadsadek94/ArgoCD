<?php

namespace App\Domains\Payments\Features\Api\V2\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Http\Resources\Api\V2\SpecialtyAreaResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use App\Domains\Partner\Repositories\CourseLevelRepository;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathCollection;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\UserSubscription;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class AssignLearnPathToUserFeature extends Feature
{


    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    public function handle(
        LearnPathInfoRepositoryInterface $learnPathRepo,
        Request $request
    )
    {
        $user = $request->user('api');;

        $proSubscription = $user->hasActiveSubscription();

        if (empty($proSubscription)) {
            $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'user',
                    'message' => 'You must have active Pro subscription',
                ]
            ]);
        }

        $learnPath = $learnPathRepo->getModel()->find($request->id);
        $packages = $learnPath->package_subscription;
        if(count($packages) > 0) {
            UserSubscription::updateOrCreate([
                'user_id' => $user->id,
                'package_id' => $packages[0]->id,
                'subscription_id' => $proSubscription->subscription_id
            ], [
                'expired_at' => $proSubscription->expired_at,
                'status' => $proSubscription->status ?? SubscribeStatus::ACTIVE
            ]);
        }else {
            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    "message" => 'success'
                ]
            ]);
        }




    }
}

