<?php

namespace App\Domains\User\Features\Api\UpdatePhone;

use INTCore\OneARTFoundation\Feature;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\User\Jobs\Api\UpdatePhone\RequestUpdatePhoneJob;
use App\Domains\User\Http\Requests\Api\V1\User\UpdatePhoneRequest;

class RequestUpdatePhoneFeature extends Feature
{
    use Authenticated;

    public function handle(UpdatePhoneRequest $request)
    {
        $auth = $this->auth('api');

        if($auth->phone == $request->phone) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'phone',
                    'message' => trans('user::lang.update_phone_with_the_same_phone')
                ]
            ]);
        }

        $this->run(RequestUpdatePhoneJob::class, [
            'auth'  => $auth,
            'phone' => $request->phone
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                "message" => trans("user::lang.otp_sent_mobile")
            ]
        ]);
    }
}
