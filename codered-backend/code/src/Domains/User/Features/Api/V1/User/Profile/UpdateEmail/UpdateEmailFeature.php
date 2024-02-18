<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\UpdateEmail;

use App\Domains\User\Http\Requests\Api\V1\User\VerifyUpdateEmailRequest;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\User\Jobs\Api\V1\User\Profile\UpdateEmail\UpdateEmailJob;
use App\Domains\User\Jobs\Api\V1\User\Profile\UpdateEmail\ValidateTempCodeJob;

class UpdateEmailFeature extends Feature
{
    public function handle(VerifyUpdateEmailRequest $request)
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

        $this->run(UpdateEmailJob::class, [
            'user' => $auth,
            'email' => $request->email
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                "message" => trans('user::lang.email_changed')
            ]
        ]);

    }
}
