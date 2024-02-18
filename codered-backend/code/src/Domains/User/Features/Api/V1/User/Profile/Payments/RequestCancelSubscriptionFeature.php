<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\Payments;

use App\Domains\Payments\Models\SubscriptionCancellationRequest;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Http\Requests\Api\V1\User\RequestSubscriptionCancellationRequest;

class RequestCancelSubscriptionFeature extends Feature
{
    public function handle(RequestSubscriptionCancellationRequest $request)
    {
        $user = $request->user('api');
        $data = $request->except(['status']);
        $data['user_id'] = $user->id;
        SubscriptionCancellationRequest::create($data);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }
}
