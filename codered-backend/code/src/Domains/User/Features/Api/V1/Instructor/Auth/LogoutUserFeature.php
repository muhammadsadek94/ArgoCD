<?php

namespace App\Domains\User\Features\Api\V1\Instructor\Auth;

use App\Domains\User\Jobs\Api\V1\Instructor\Auth\LogoutUserJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class LogoutUserFeature extends Feature
{
    public function handle(Request $request)
    {

        $user = $this->run(LogoutUserJob::class, [
            'token' => $request->user('api')->token()
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => 'revoked'
        ]);
    }
}
