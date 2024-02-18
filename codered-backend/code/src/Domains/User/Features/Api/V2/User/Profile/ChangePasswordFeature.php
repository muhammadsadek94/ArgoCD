<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use App\Domains\User\Http\Requests\Api\V2\User\ChangePasswordRequest;
use App\Domains\User\Jobs\Api\V2\User\Profile\ChangePasswordJob;
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
        if($newToken == ChangePasswordJob::ERROR_OLD_PASSWORD_NOT_CORRECT) {
            return $this->run(RespondWithJsonErrorJob::class, [
               "errors" => [
                   "name" => "message",
                   "message" => trans("user::lang.current_password_is_invalid")
               ]
            ]);
        }

        if($newToken == ChangePasswordJob::ERROR_NEW_PASSWORD_IS_SAME_AS_OLD) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    "name" => "message",
                    "message" => trans("The new password must be different than old one.")
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
