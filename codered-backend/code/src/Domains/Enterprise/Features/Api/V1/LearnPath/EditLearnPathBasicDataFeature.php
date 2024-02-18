<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathsDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\LearnPath\UpdateLearnPathJob;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class EditLearnPathBasicDataFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    use Authenticated;

    public function handle(Request $request)
    {
        $admin = $this->auth('api');
        $packageSubscription = PackageSubscription::active()->where('id', $request->learn_path)->where('enterprise_id', $admin->id)->first();

        if (!$packageSubscription) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'message',
                    "message" => 'learning path not found'
                ]
            ]);
        }
        $packageSubscription = $this->run(UpdateLearnPathJob::class, [
            'request' => $request,
            'packageSubscription' => $packageSubscription
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                    'learn_path' => new EnterpriseLearnPathsDetailsResource($packageSubscription),
            ]
        ]);
    }
}
