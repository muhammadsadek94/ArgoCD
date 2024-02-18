<?php

namespace App\Domains\User\Http\Network;

use App\Foundation\Http\Network\HttpHandler;

class LinkedInAuthenticationApi extends HttpHandler
{

    //    const GET_CODE = 'https://www.linkedin.com/uas/oauth2/authorization';

    const GET_ACCESS_TOKEN = 'https://www.linkedin.com/uas/oauth2/accessToken';

    const GET_EMAIL_ADDRESS = 'https://api.linkedin.com/v2/emailAddress';

    const GET_USER_PROFILE = 'https://api.linkedin.com/v2/me';

    public function __construct()
    {

        parent::__construct(null);

    }

    public function getAccessToken($code, $redirect_url)
    {
        $parameter = [
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $redirect_url,
            'client_id'     => config('user.linkedin.client_id'),
            'client_secret' => config('user.linkedin.client_secret'),
            'code'          => $code
        ];
        return $this->get(self::GET_ACCESS_TOKEN, $parameter);
    }

    public function getProfile($accessToken)
    {

        $headers = [
            'Authorization' => "Bearer {$accessToken}"
        ];
        return $this->get(self::GET_USER_PROFILE, [], [], [], $headers);
    }

    public function getEmail($accessToken)
    {
        $headers = [
            'Authorization' => "Bearer {$accessToken}"
        ];

        $parameter = [
            'q'          => 'members',
            'projection' => '(elements*(handle~))'
        ];
        return $this->get(self::GET_EMAIL_ADDRESS, $parameter, [], [], $headers);
    }

}
