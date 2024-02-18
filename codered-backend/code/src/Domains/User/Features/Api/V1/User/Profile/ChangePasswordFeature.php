<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use App\Domains\User\Http\Requests\Api\V1\User\ChangePasswordRequest;
use App\Domains\User\Jobs\Api\V1\User\Profile\ChangePasswordJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class ChangePasswordFeature extends Feature
{
    public function handle(ChangePasswordRequest $request)
    {
        $user = $request->user();

        $newToken = $this->run(ChangePasswordJob::class, [
            "request" => $request,
            "user" => $user
        ]);
        if(!$newToken) {
            return $this->run(RespondWithJsonErrorJob::class, [
               "errors" => [
                   "name" => "message",
                   "message" => trans("user::lang.current_password_is_invalid")
               ]
            ]);
        }
        return $this->run(RespondWithJsonJob::class, [
           "content" => [
               "token" => $newToken,
               "message" => trans("user::lang.password_changed")
           ]
        ]);
    }
}
