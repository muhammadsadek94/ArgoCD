<?php

namespace App\Domains\User\Features\Api\V1\User\Auth;

use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Domains\User\Http\Requests\Api\V1\User\RegisterUserWithoutPasswordRequest;
use App\Domains\User\Jobs\Api\V1\Device\UpdateDeviceInfoJob;
use App\Domains\User\Http\Requests\Api\V1\User\RegisterUserRequest;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;
use App\Domains\User\Jobs\Api\V1\User\Activation\SendOtpActivationCodeJob;
use App\Domains\User\Jobs\Api\V1\User\Auth\RegisterUserJob;
use App\Domains\Geography\Models\Country;
use App\Domains\User\Jobs\Api\V1\User\Auth\RegisterUserWithoutPasswordJob;
use App\Domains\User\Mails\SendPasswordMail;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Support\Facades\Mail;
use INTCore\OneARTFoundation\Feature;
use Constants;

class RegisterUserWithoutPasswordFeature extends Feature
{
    public function handle(RegisterUserWithoutPasswordRequest $request)
    {


        // register user in database
        $user = $this->run(RegisterUserWithoutPasswordJob::class, [
            'request' => $request,
        ]);
        $this->setPasswordForNewUser($user);


        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                "status" => true
            ]
        ]);
    }

    private function setPasswordForNewUser(User $user)
    {

        $random_pass = \Str::random(20);
        $user->update([
            'password' => $random_pass
        ]);

        $subject = "Your EC-Council Learning Account Details";
        Mail::to($user->email)->send(new SendPasswordMail($subject, $random_pass, $user));
    }
}
