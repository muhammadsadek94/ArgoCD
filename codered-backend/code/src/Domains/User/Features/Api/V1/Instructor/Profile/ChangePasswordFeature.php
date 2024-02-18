<?php

namespace App\Domains\User\Features\Api\V1\Instructor\Profile;

use App\Domains\User\Http\Requests\Api\V1\Instructor\ChangePasswordRequest;
use App\Domains\User\Jobs\Api\V1\Instructor\Profile\ChangePasswordJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class ChangePasswordFeature extends Feature
{
    public function handle(ChangePasswordRequest $request)
    {
        $user = $request->user();

        $isUpdated = $this->run(ChangePasswordJob::class, [
            "request" => $request,
            "user" => $user
        ]);

        if(!$isUpdated) {
            return $this->run(RespondWithJsonErrorJob::class, [
               "errors" => [
                   "name" => "message",
                   "message" => trans("user::lang.current_password_is_invalid")
               ]
            ]);
        }
        return $this->run(RespondWithJsonJob::class, [
           "content" => [
               "message" => trans("user::lang.password_changed")
           ]
        ]);
    }
}
