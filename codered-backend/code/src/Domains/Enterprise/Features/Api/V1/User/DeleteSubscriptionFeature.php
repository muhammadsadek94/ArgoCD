<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\User\Jobs\Api\V1\User\Profile\DeleteSubscriptionJob;
use App\Foundation\Traits\Authenticated;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\CourseInfoResource;


class DeleteSubscriptionFeature extends Feature
{
    use Authenticated;

    public function handle(Request $request)
    {
        $status = $this->run(DeleteSubscriptionJob::class, [
            'request'       => $request,
        ]);


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'subscription' => 'has been deleted successfully',
            ]
        ]);

//
//        $user = $this->run(DeleteSubscriptionJob::class, [
//            'request' => $request,
//        ]);
//
//        return $this->run(RespondWithJsonJob::class, [
//            'content' => new UserInfoResource($user)
//        ]);
    }
}
