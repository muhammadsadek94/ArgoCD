<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use App\Domains\User\Http\Requests\Api\V1\User\ChangePasswordRequest;
use App\Domains\User\Http\Requests\Api\V1\User\CheckPasswordRequest;
use App\Domains\User\Jobs\Api\V1\User\Profile\ChangePasswordJob;
use App\Domains\User\Jobs\Api\V1\User\Profile\CheckPasswordJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class CheckPasswordFeature extends Feature
{
    public function handle(CheckPasswordRequest $request)
    {
        $user = $request->user();

        $password = $this->run(CheckPasswordJob::class, [
            "request" => $request,
            "user" => $user
        ]);
        if(!$password) {
            return $this->run(RespondWithJsonErrorJob::class, [
               "errors" => [
                   "name" => "message",
                   "message" => trans("user::lang.password_is_invalid")
               ]
            ]);
        }
        return $this->run(RespondWithJsonJob::class, [
           "content" => [
               "message" => trans("user::lang.Password Correct")
           ]
        ]);
    }
}
