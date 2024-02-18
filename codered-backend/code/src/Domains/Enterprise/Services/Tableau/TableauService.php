<?php


namespace App\Domains\Enterprise\Services\Tableau;


use App\Domains\User\Models\User;
use App\Foundation\Http\Network\HttpHandler;
use Log;

class TableauService extends HttpHandler
{
    private $base_url;
    private $name;
    private $password;

    public function __construct()
    {

//        $this->base_url = config('course.services.cyberq.end_point');
        $this->base_url = env('TABLEAU_END_POINT');
        $this->name = env('TABLEAU_USER');
        $this->password = env('TABLEAU_PASSWORD');
        parent::__construct($this->base_url);
    }

    private function authenticate(): object
    {
        $data = [
            "credentials" => [
                'name' => $this->name,
                'password' => $this->password,
                'site' => [
                    'contentUrl' => 'codered'
                ],
            ]];


        $response = $this->post('/api/3.1/auth/signin', [], [], $data);
        return $response;
    }

    public function CreateTicket($target_site)
    {
//        $response = $this->authenticate();
//        Log::error("Tableau auth : " . collect($response)->toJson());
//        if (!array_key_exists('error', $response)) {
//            $token = $response->data['credentials']['token'];
        $url = $this->base_url . "/trusted";
        $username = $this->name;
        $site = $target_site;
        $data = array('username' => $username, 'target_site' => $site);
        $options = array(
            'http' => array(
                'method' => 'POST',
                "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;

//        }
//        return 1;
    }

}
