<?php

namespace App\Domains\Course\Services\CyberQ;

use App\Domains\Admin\Traits\Auth\User as AuthUser;
use App\Domains\Course\Models\CyberQTokens;
use App\Domains\User\Services\GetUserCountryByIpServices;
use App\Domains\User\Models\User;
use App\Foundation\Http\Network\HttpHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
use Str;

class CyberQService extends HttpHandler
{
    private $base_url;

    public function __construct()
    {
        $this->base_url = config('course.services.cyberq.end_point');
        parent::__construct($this->base_url);
    }

    /**
     * @param User|AuthUser $user
     * @param null          $user_display_name
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticate(User|AuthUser $user, $user_display_name = null): object
    {
        $data = [
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name ?? 'Codered',
            'username'    => $user->email ?? 'Codered',
            'accesstoken' => config('course.services.cyberq.api_token'),
            'displayname' => $user_display_name ?? $user->display_name,
        ];

        $response = $this->post('api/v2/lab/user/login/', [], [], $data);
        return $response;
    }

    public function updateDisplayName(User $user, $user_display_name, $token)
    {
        $data = [
            'displayname' => $user_display_name,
        ];
        $header_data = [
            'Authorization' => 'Token ' . $token,
        ];
        $response = $this->post('api/v1/ec/event/update_user_display/', [], [], $data, $header_data);
        return $response;
    }

    public function createCyberQ(User $user, $cyberq_id, $ip_address, $course_enrollment = null, $course = null)
    {

        $access_token = Str::random(8);
        // save this hash key in the database
        CyberQTokens::create([
            'user_id'      => $user->id,
            'access_token' => $access_token,
            'expired_at'   => now()->addDay()
        ]);

        $data = new \StdClass;
        $data->data['success'] = true;
        $data->data['Url'] = "{$this->base_url}/lab/{$cyberq_id}/{$access_token}";

        return $data;

        //        return [
        //            'success' => true,
        //            'Url'     => "{$this->base_url}/lab/{$cyberq_id}/{$access_token}"
        //        ];

        //        $response = $this->authenticate($user);

        //        $zone = $this->getZone($ip_address);


        //        if ($response->data['success']) {
        //            $response->data['Url'] = "{$this->base_url}/lab/{$cyberq_id}/{$response->data['data']['token']}/eu-frankfurt-1";
        //            return $response;
        //        }
    }

    public function validateToken($token)
    {
        $cyberQ = CyberQTokens::where(['access_token' => $token])
            ->whereRaw("BINARY access_token COLLATE utf8mb4_bin = BINARY '$token'")
            ->first();

        if (empty($cyberQ)) return;

        $user = $cyberQ->user;

        $cyberQ->update(['access_token' => null]);

        return [
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name ?? 'Codered',
            'username'    => $user->email ?? 'Codered',
            'accesstoken' => config('course.services.cyberq.api_token'),
            'displayname' => $user->display_name,
        ];
    }

    private function getZone($ip_address)
    {

        $user_country = new GetUserCountryByIpServices();
        $country_details = $user_country->GetUserCountryByIp($ip_address);
        $user_continent = $country_details->data['continent_name'] ?? 'Europe';

        if ($user_continent == "Europe" || $user_continent == "Africa") {

            $zone = "eu-frankfurt-1";
        }

        if ($user_continent == "Asia" || $user_continent == "Australia" || $user_continent == "New Zealand") {

            $zone = "ap-singapore-1";
        }

        if ($user_continent == "North America" || $user_continent == "Latin America") {

            $zone = "s-tampa-1";
        }

        return $zone;
    }

    private function updatePurchaseDetails($response, $lab_id, $course_enrollment = null, $course = null)
    {
        $token = $response->data['data']['token'];

        $header_data = [
            'Authorization' => 'Token ' . $token,
        ];

        $data = [
            'skill-pack'  => [$course?->cyberq_course_id],
            'experiences' => [$lab_id],
            'key'         => 6,
            'end_date'    => $course_enrollment ? Carbon::parse($course_enrollment?->expired_at)->format('Y-m-d H:i:s') : null,
        ];

        $response = $this->post('api/v2/lab/cq/purchase-details/', [], [], $data, $header_data);

        return $response;
    }
}
