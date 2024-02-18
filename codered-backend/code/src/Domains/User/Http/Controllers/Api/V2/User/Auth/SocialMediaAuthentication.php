<?php

namespace App\Domains\User\Http\Controllers\Api\V2\User\Auth;

use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Features\Api\V2\User\Auth\SocialMediaLoginFeature;
use App\Domains\User\Features\Api\V2\User\Auth\SocialMediaRegisterFeature;
use App\Domains\User\Http\Network\GithubAuthenticationApi;
use App\Domains\User\Http\Network\LinkedInAuthenticationApi;
use App\Domains\User\Http\Network\TwitterAuthenticationApi;
use App\Domains\User\Http\Resources\Api\V2\User\UserDetailsResource;
use App\Domains\User\Models\User as ModelsUser;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\Controller;
use Redirect;
// use Socialite;

class SocialMediaAuthentication extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return $this->serve(SocialMediaLoginFeature::class);
    }

    /**
     * authorize user to system.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return $this->serve(SocialMediaRegisterFeature::class);
    }

    public function getLinkedIn()
    {
        $api = new LinkedInAuthenticationApi();

        $code = request('code');
        $redirect_id = request('redirect_url');

        $response_code = $api->getAccessToken($code, $redirect_id);
        $getCodeResponseToArray = collect($response_code->data)->toArray();
        $access_token = $getCodeResponseToArray['access_token'];

        $profile = $api->getProfile($access_token);
        $email = $api->getEmail($access_token);

        return [
            'profile' => $profile,
            'email' => $email,
        ];
    }

    public function getGithub()
    {
        $api = new GithubAuthenticationApi();

        $code = request('code');
        $redirect_id = request('redirect_url');

        $response_code = $api->getAccessToken($code, $redirect_id);
        $getCodeResponseToArray = collect($response_code->data)->toArray();
        $access_token = $getCodeResponseToArray['access_token'];

        $profile = $api->getProfile($access_token);
        $email = $api->getEmail($access_token)->data[0]['email'];

        $emailExists = ModelsUser::where('email', $email)->first();

        $authUser = ModelsUser::where('social_id', $profile->data['id'])->first();

        if((!$authUser && $emailExists)){
            return [
                'message' => 'email is already exists',
            ];
        }

        if (!$authUser) { 
            $authUser = ModelsUser::create([
                'first_name' => $profile->data['login'],
                'email'    => $email,
                'social_type' => 'github',
                'social_id' => $profile->data['id'],
                'type'  => UserType::USER,
                'activation' => UserActivation::COMPLETE_PROFILE
            ]);
        }

        $authUser->access_token = $authUser->createToken(null);

        return $this->dispatch(new RespondWithJsonJob(new UserDetailsResource($authUser)));
    }    

    public function getTwitter(){

        $api = new TwitterAuthenticationApi();

        $code = request('code');
        $redirect_id = request('redirect_url');

        $response_code = $api->getAccessToken($code, $redirect_id);
        $getCodeResponseToArray = collect($response_code->data)->toArray();
        $access_token = $getCodeResponseToArray['access_token'];

        $profile = $api->getProfile($access_token);

        $social_id = $profile->data['data']['id'];

        $name = $profile->data['data']['name'];

        $nameArray = explode(" ", $name);

        if(count($nameArray) > 1){
            $firstName = $nameArray[0];
            $lastName = $nameArray[1];
        }
        else{
            $firstName = $name;
        }


        $emailExists = ModelsUser::where('email', $social_id.'@twitter.com')->first();

        $authUser = ModelsUser::where('social_id', $social_id)->first();

        if((!$authUser && $emailExists)){
            return [
                'message' => 'email is already exists',
            ];
        }

        if (!$authUser) { 
            $authUser = ModelsUser::create([
                'first_name' => $firstName,
                'last_name' => $lastName ?? null,
                'email'    => $social_id.'@twitter.com',
                'social_type' => 'twitter',
                'social_id' => $social_id,
                'type'  => UserType::USER,
                'activation' => UserActivation::COMPLETE_PROFILE
            ]);
        }

        $authUser->access_token = $authUser->createToken(null);

        return $this->dispatch(new RespondWithJsonJob(new UserDetailsResource($authUser)));

    }
}
