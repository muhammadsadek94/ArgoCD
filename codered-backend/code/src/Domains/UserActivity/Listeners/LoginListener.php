<?php

namespace App\Domains\UserActivity\Listeners;

use App\Domains\Admin\Models\Admin;
use App\Domains\User\Models\User;
use App\Domains\UserActivity\Models\UserActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginListener
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        if (!config('user-activity.log_events.on_login', false)) return;

        $user = $event->user;
        if(!$user instanceof Admin) return ;

        $data = [
            'ip'         => $this->request->ip(),
            'user_agent' => $this->request->userAgent()
        ];

        UserActivityLog::create([
            'user_id'    => $user->id,
            'table_name' => '',
            'log_type'   => 'login',
            'data'       => json_encode($data)
        ]);
    }
}
