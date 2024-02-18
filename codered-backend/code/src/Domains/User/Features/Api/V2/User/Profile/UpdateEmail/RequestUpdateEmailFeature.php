<?php

namespace App\Domains\User\Features\Api\V2\User\Profile\UpdateEmail;

use INTCore\OneARTFoundation\Feature;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\User\Http\Requests\Api\V2\User\UpdateEmailRequest;
use App\Domains\User\Jobs\Api\V2\User\Profile\UpdateEmail\RequestUpdateEmailJob;

class RequestUpdateEmailFeature extends Feature
{
    use Authenticated;

    public function handle(UpdateEmailRequest $request)
    {
        $auth = $this->auth('api');

        if($auth->email == $request->email) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'email',
                    'message' => trans('user::lang.update_email_with_the_same_email')
                ]
            ]);
        }
        $this->run(RequestUpdateEmailJob::class, [
            'auth'  => $auth,
            'email' => $request->email
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                "message" => trans("user::lang.otp_sent_email")
            ]
        ]);
    }
}
