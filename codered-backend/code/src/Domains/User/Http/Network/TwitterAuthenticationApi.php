<?php

namespace App\Domains\User\Http\Network;

use App\Foundation\Http\Network\HttpHandler;

class TwitterAuthenticationApi extends HttpHandler
{

    const GET_ACCESS_TOKEN = 'https://api.twitter.com/2/oauth2/token';

    const GET_USER_PROFILE = 'https://api.twitter.com/2/users/me';

    public function __construct()
    {

        parent::__construct(null);

    }
//TODO Change Hardcoded
    public function getAccessToken($code, $redirect_url)
    {
        $parameter = [
            'redirect_uri'  => 'https://codered-beta-test.eccouncil.org:8443/login',
            'client_id'     => env('TWITTER_ID'),
            'code'          => $code,
            'grant_type'    => 'authorization_code',
            'code_verifier' => 'U3mrU4Lb6F7zSMuBz5Y-HoGp-8yXP44qOfQhr5ACIWP3lUfAvebgA0PZuuivRliuOhVC2h-MSyV66G9OOGwstyuB6U4b88BebrAP.rA6hPeF9uQbf9n2tJEN91k9cqCu',
        ];

        $client_id = env('TWITTER_ID');
        $client_secret = env('TWITTER_SECRET');

        $headers = [
            'Authorization' => "Basic ". base64_encode($client_id.':'.$client_secret)
        ];

        return $this->post(self::GET_ACCESS_TOKEN, $parameter, [], [], $headers);
    }

    public function getProfile($accessToken)
    {

        $headers = [
            'Authorization' => "Bearer {$accessToken}"
        ];
        return $this->get(self::GET_USER_PROFILE, [], [], [], $headers);
    }

}
