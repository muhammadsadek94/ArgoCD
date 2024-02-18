<?php

namespace App\Domains\Course\Services\ILab;

use App\Domains\User\Models\User;
use App\Foundation\Http\Network\HttpHandler;

class ILabService extends HttpHandler
{
    public function __construct()
    {
        $base_url = 'https://labondemand.com/api/v3';
        parent::__construct($base_url);
    }

    public function createLab($ilabId, User $user, $ipAddress = '1.1.1.1')
    {
        $query = [
            'labid'               => $ilabId,
            'userid'              => $user->id,
            'firstname'           => empty($user->first_name) ? 'Codered' : $user->first_name,
            'lastname'            => empty($user->last_name) ? 'Codered' : $user->last_name,
            'email'               => $user->email,
            'ipAddress'           => $ipAddress,
            'canBeMarkedComplete' => 1,
            'maxSavedLabs'        => 1,
            'maxActiveLabs'       => 1,
            'lang'                => 'en'
        ];

        $headers = [
            'api_key' => config('course.services.ilab.api_token')
        ];
        return $this->get('/launch', $query, [], [], $headers);
    }

}
