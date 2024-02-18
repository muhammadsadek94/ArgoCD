<?php

namespace App\Domains\Notification\Features\Api;

use App\Domains\Notification\Http\Resources\GetMyNotificationCollection;
use App\Domains\Notification\Jobs\Api\GetMyNotificationJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class GetMyNotificationFeature extends Feature
{
    public function handle(Request $request)
    {
        $user = $request->user('api');

        $notifications = $this->run(GetMyNotificationJob::class, [
            "request"  => $request,
            "perPage"  => 15,
            "user" => $user
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => new GetMyNotificationCollection($notifications)
        ]);
    }
}
