<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use App\Domains\User\Jobs\Api\V1\User\Profile\SetSelectedDaysJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class SetSelectedDaysFeature extends Feature
{

    public function handle(Request $request)
    {

        $this->run(SetSelectedDaysJob::class, [
            'user' => $request->user(),
            'target' => $request->target,
            'course_id' => $request->course_id
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }
}
