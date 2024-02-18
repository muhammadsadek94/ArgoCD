<?php

namespace App\Domains\Course\Services\VitalSource;

use App\Domains\User\Models\User;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Support\Facades\Http;
use Faker\Generator as Faker;
use Str;
use Illuminate\Support\Facades\Crypt;

class VitalSourceService
{

    private $client;

    const USER_ENDPOINT = 'https://api.vitalsource.com/v3/users.xml';
    const CREDENTIALS_ENDPOINT = 'https://api.vitalsource.com/v3/credentials.xml';
    const FULFILLMENT_ENDPOINT = 'https://api.vitalsource.com/v4/fulfillments';
    const REDIRECTION_ENDPOINT = 'https://api.vitalsource.com/v3/redirects.xml';
    const NEW_FULFILLMENT_ENDPOINT= 'https://bookshelf.vitalsource.com/books/';


    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAccessToken(User $user, $from_get_access)
    {
        $last_name = $user->last_name ?? 'Codered';

        $xml = "<?xml version='1.0' encoding='UTF-8'?>
        <user>
         <reference>$user->id</reference>
         <first-name>$user->first_name</first-name>
         <last-name>$last_name</last-name>
        </user>";

        $options = [
            'headers' => [
                'X-VitalSource-API-Key' => config('course.services.vital_source.api_token'),
                'Content-Type' => 'text/xml',
            ],
            'body' => $xml,
        ];

        $response = $this->client->request('POST', self::USER_ENDPOINT, $options);

        $xml = simplexml_load_string($response->getBody()->getContents());
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        // dd($array);
        if(array_key_exists('error-code', $array) && !$from_get_access) {
            return $this->verifyCredentials($user , $from_get_access = true);
        }

        if(array_key_exists('error-code', $array) && array_key_exists('error', $array)&& $from_get_access) {
            return $array['error'];
        }
        if(array_key_exists('error-code', $array)) {
            if($array['error-code']=='464'){
                return ["message"=>"You already have account in Vital Source"];
            }
        }
        if(array_key_exists('access-token', $array)) {
            return $array['access-token'];
        }
    }

    public function verifyCredentials(User $user , $from_get_access = false)
    {

        $user_reference = $user->id;

        $xml = "<?xml version='1.0' encoding='UTF-8'?>
                <credentials>
                    <credential reference='$user_reference' />
                </credentials>";

        $options = [
            'headers' => [
                'X-VitalSource-API-Key' => config('course.services.vital_source.api_token'),
                'Content-Type' => 'text/xml',
            ],
            'body' => $xml,
        ];

        $response = $this->client->request('POST', self::CREDENTIALS_ENDPOINT, $options);

        $xml = simplexml_load_string($response->getBody()->getContents());
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        if(array_key_exists('error', $array) &&  array_key_exists('@attributes', $array['error'])){
            if($array['error']['@attributes']['code'] == '603') {
                // dd($array);
                // $user->update(['vital_source_ref' => Str::random(4).substr($user->id,0,5).'@vital.com' ]);
                return $this->getAccessToken($user , $from_get_access);
            }
        }

        if(array_key_exists('credential', $array) && array_key_exists('@attributes', $array['credential']) && array_key_exists('access-token', $array['credential']['@attributes'])) {
            return $array['credential']['@attributes']['access-token'];
        }

    }

    public function giveUserAccessToBook(string $access_token, string $book_id)
    {

        $res = Http::withHeaders([
            'Accept'                        => '*/*',
            'X-VitalSource-API-Key'         => config('course.services.vital_source.api_token'),
            'X-VitalSource-Access-Token'    => $access_token,
            'Content-Type'                  => 'application/json'
        ])->post(self::FULFILLMENT_ENDPOINT, [
               "fulfillment" => [
                   "sku" => $book_id,
                   "term" => "no access",
                   "online_term" => "180",
                   "ensure_days" => "",
                   "ensure_online_days" => "365",
                   "tag" => "codered"
               ]
           ]);

        // dd($res->json(),self::NEW_FULFILLMENT_ENDPOINT.$book_id);
        if(array_key_exists('code', $res->json())) {
             return $res->json()['code'];
        }
    }

    public function redirectUrl(string $access_token, string $book_id)
    {

        $xml = "<?xml version='1.0' encoding='UTF-8'?>
                <redirect>
                    <destination>https://online.vitalsource.com/books/$book_id</destination>
                    <brand>online.vitalsource.com</brand>
                </redirect>";

        $options = [
            'headers' => [
                'X-VitalSource-API-Key'         => config('course.services.vital_source.api_token'),
                'X-VitalSource-Access-Token'    => $access_token,
                'Content-Type' => 'text/xml',
            ],
            'body' => $xml,
        ];

        $response = $this->client->request('POST', self::REDIRECTION_ENDPOINT, $options);

        $xml = simplexml_load_string($response->getBody()->getContents());
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        if(array_key_exists('@attributes', $array) && array_key_exists('auto-signin', $array['@attributes'])) {
            return $array['@attributes']['auto-signin'];
        }
    }

    public function redirectUrlWithPageNumber(string $access_token, string $book_id, string $page_number = null)
    {

        $xml = "<?xml version='1.0' encoding='UTF-8'?>
                <redirect>
                    <destination>https://online.vitalsource.com/books/$book_id/pageid/$page_number</destination>
                    <brand>online.vitalsource.com</brand>
                </redirect>";

        $options = [
            'headers' => [
                'X-VitalSource-API-Key'         => config('course.services.vital_source.api_token'),
                'X-VitalSource-Access-Token'    => $access_token,
                'Content-Type' => 'text/xml',
            ],
            'body' => $xml,
        ];

        $response = $this->client->request('POST', self::REDIRECTION_ENDPOINT, $options);

        $xml = simplexml_load_string($response->getBody()->getContents());
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        if(array_key_exists('@attributes', $array) && array_key_exists('auto-signin', $array['@attributes'])) {
            return $array['@attributes']['auto-signin'];
        }
    }

}
