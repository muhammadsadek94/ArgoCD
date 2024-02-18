<?php


namespace App\Domains\User\Services;


use App\Domains\User\Events\User\PasswordUpdated;
use App\Domains\User\Events\User\UserUpdated;
use App\Domains\User\Models\User;
use App\Foundation\Http\Network\HttpHandler;
use Illuminate\Support\Facades\Log;

class GetUserCountryByIpServices extends HttpHandler
{
    private $base_url;
    private $access_key;

    public function __construct()
    {
        $this->base_url = config('user.services.ipstack.base_url');
        $this->access_key =  config('user.services.ipstack.access_key');

        parent::__construct($this->base_url);
    }


    public function GetUserCountryByIp($ip): object
    {
        $response = $this->get("{$ip}", ['access_key' => $this->access_key], []);
        //dd($response);
        return $response;
    }
}
