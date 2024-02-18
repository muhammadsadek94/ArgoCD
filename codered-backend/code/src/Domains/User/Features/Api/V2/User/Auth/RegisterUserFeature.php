<?php

namespace App\Domains\User\Features\Api\V2\User\Auth;

use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Domains\User\Jobs\Api\V2\Device\UpdateDeviceInfoJob;
use App\Domains\User\Http\Requests\Api\V2\User\RegisterUserRequest;
use App\Domains\User\Http\Resources\Api\V2\User\UserDetailsResource;
use App\Domains\User\Jobs\Api\V2\User\Activation\SendOtpActivationCodeJob;
use App\Domains\User\Jobs\Api\V2\User\Auth\RegisterUserJob;
use App\Domains\Geography\Models\Country;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Constants;

class RegisterUserFeature extends Feature
{
    public function handle(RegisterUserRequest $request)
    {
        if (User::where('email', $request->email)->exists()){
            return $this->run(RespondWithJsonErrorJob::class, [
               'errors' => [
                   "message"=> "The email has already been taken.",
                   "name" => "email"
               ]
            ], 422);
        }


        // register user in database
        $user = $this->run(RegisterUserJob::class, [
            'request' => $request,
        ]);


        // send otp
        // $this->run(SendOtpActivationCodeJob::class, [
        //     "user" =>  $user,
        //     'auto_activate' => true
        // ]);

        // mark user profile image in use
        if($request->has('image_id')) {
            $this->run(MarkFileAsUsedJob::class, [
                'file_id' => $request->image_id
            ]);
        }

        //update device token & language
        $this->run(UpdateDeviceInfoJob::class, [
            'token'     => $user->access_token->token,
            'device_id' => $request->device_id ?? null,
            'language'  => $request->get('language') ?? $request->header('Accept-Language') ?? Constants::DEFAULT_LANGUAGE
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => new UserDetailsResource($user),
            'status' => 201
        ]);
    }
}
