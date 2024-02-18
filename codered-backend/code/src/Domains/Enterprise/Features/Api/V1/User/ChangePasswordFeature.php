<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\User\Jobs\Api\V1\User\Profile\ChangePasswordJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class ChangePasswordFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(Request $request)
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
