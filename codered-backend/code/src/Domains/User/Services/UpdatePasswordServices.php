<?php


namespace App\Domains\User\Services;


use App\Domains\User\Events\User\PasswordUpdated;
use App\Domains\User\Events\User\UserUpdated;
use App\Domains\User\Models\User;
use App\Foundation\Http\Network\HttpHandler;
use Illuminate\Support\Facades\Log;

class UpdatePasswordServices extends HttpHandler
{
    private $base_url;

    public function __construct()
    {
        $this->base_url = config('user.services.update_password.base_url');
        parent::__construct($this->base_url);
    }


    public function sendMessageForUpdatedPasswordEvent(PasswordUpdated $event): object
    {
        $data = [
            'action' => "password-reset",
            'data' => [
                'email' => $event->user->email,
                'Message' => 'password is updated by the user from an external source'
            ]
        ];


        $response = $this->post('', [], [], $data);
        return $response;
    }

}
