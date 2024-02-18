<?php

namespace App\Domains\User\Features\Api\V2\User\Device;

use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\User\Jobs\Api\V2\Device\UpdateDeviceInfoJob;
use Constants;

class UpdateDeviceInfoFeature extends Feature
{
    use Authenticated;

    public function handle(Request $request)
    {
        $user = $this->auth('api');

        $this->run(UpdateDeviceInfoJob::class, [
            'token'     => $user->token(),
            'device_id' => $request->device_id ?? $user->token()->device_id,
            'language'  => $request->get('language') ?? $request->header('Accept-Language') ?? Constants::DEFAULT_LANGUAGE
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }
}
