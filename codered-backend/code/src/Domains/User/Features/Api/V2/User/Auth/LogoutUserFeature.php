<?php

namespace App\Domains\User\Features\Api\V2\User\Auth;

use App\Domains\User\Jobs\Api\V2\User\Auth\LogoutUserJob;
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
