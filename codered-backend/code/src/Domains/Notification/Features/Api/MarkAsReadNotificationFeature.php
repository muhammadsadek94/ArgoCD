<?php

namespace App\Domains\Notification\Features\Api;

use App\Domains\Notification\Http\Resources\GetMyNotificationCollection;
use App\Domains\Notification\Jobs\Api\MarkAsReadNotificationJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class MarkAsReadNotificationFeature extends Feature
{
    public function handle(Request $request)
    {

        $user = $request->user('api');
        $notification = $this->run(MarkAsReadNotificationJob::class, [
            "request" => $request,
            "perPage" => 15,
            "user"    => $user
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'message' => 'success'
            ]
        ]);
    }
}
