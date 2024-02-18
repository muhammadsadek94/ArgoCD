<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Jobs\Api\V2\User\Profile\SetDailyGoalJob;


class SetDailyGoalFeature extends Feature
{

    public function handle(Request $request)
    {

        $this->run(SetDailyGoalJob::class, [
            'user' => $request->user(),
            'target' => $request->target
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }
}
