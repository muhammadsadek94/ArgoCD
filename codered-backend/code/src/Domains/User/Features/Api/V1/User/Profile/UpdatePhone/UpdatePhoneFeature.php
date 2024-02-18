<?php

namespace App\Domains\User\Features\Api\UpdatePhone;

use App\Domains\User\Jobs\Api\UpdatePhone\UpdatePhoneJob;
use App\Domains\User\Jobs\Api\UpdatePhone\ValidateTempCodeJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class UpdatePhoneFeature extends Feature
{
    public function handle(Request $request)
    {
        $auth = $request->user();

        $validation = $this->run(ValidateTempCodeJob::class, [
            'user' => $auth,
            'temp_code' => $request->temp_code
        ]);

        if ($validation == false) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'=>'message',
                    'message'=> trans('user::lang.incorrect_otp')
                ]
            ]);
        }

        $this->run(UpdatePhoneJob::class, [
            'user' => $auth,
            'temp_code' => $request->temp_code,
            'phone' => $request->phone
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                "message" => trans('user::lang.phone_updated')
            ]
        ]);

    }
}
