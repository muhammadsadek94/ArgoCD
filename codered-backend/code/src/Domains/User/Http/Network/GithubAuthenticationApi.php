<?php

namespace App\Domains\User\Http\Network;

use App\Foundation\Http\Network\HttpHandler;

class GithubAuthenticationApi extends HttpHandler
{

    //    const GET_CODE = 'https://www.linkedin.com/uas/oauth2/authorization';

    const GET_ACCESS_TOKEN = 'https://github.com/login/oauth/access_token';

    const GET_USER_PROFILE = 'https://api.github.com/user';

    const GET_EMAIL_ADDRESS = 'https://api.github.com/user/emails';

    public function __construct()
    {

        parent::__construct(null);

    }

    public function getAccessToken($code, $redirect_url)
    {
        $parameter = [
            'redirect_uri'  => $redirect_url,
            'client_id'     => env('GITHUB_ID'),
            'client_secret' => env('GITHUB_SECRET'),
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
