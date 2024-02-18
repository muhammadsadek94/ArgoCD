<?php

namespace App\Domains\User\Features\Api\V1\User\Auth;

use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Domains\User\Jobs\Api\V1\Device\UpdateDeviceInfoJob;
use App\Domains\User\Http\Requests\Api\V1\User\RegisterUserRequest;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;
use App\Domains\User\Jobs\Api\V1\User\Activation\SendOtpActivationCodeJob;
use App\Domains\User\Jobs\Api\V1\User\Auth\RegisterUserJob;
use App\Domains\Geography\Models\Country;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Constants;

class RegisterUserFeature extends Feature
{
    public function handle(RegisterUserRequest $request)
    {


        //If user did not select country on register form then trace IP and detect country//

//        if(empty($request->country_id)){
//
//             //Finding Public IP Address//
//            $ip_address=file_get_contents('http://checkip.dyndns.com/');
//            $ip_address = str_replace("Current IP Address: ","",$ip_address);
//
//            preg_match("/<body[^>]*>(.*?)<\/body>/is", $ip_address, $matches);
//
//            $public_ip = $matches[1];
//
//
//
//            //Using IPStack API to detect location//
//            $ip = $request->ip();
//            $access_key =  config('user.services.ipstack.access_key');
//
//            // Initialize CURL:
//            $ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$access_key.'');
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//            // Store the data:
//            $json = curl_exec($ch);
//            curl_close($ch);
//
//            // Decode JSON response:
//            $api_result = json_decode($json, true);
//
//            $countries = Country::where("name_en", $api_result['country_name'])->active()->pluck("id");
//
//
//            $request->country_id = $countries[0];
//
//
//
//        }

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
            'content' => new UserDetailsResource($user)
        ]);
    }
}
