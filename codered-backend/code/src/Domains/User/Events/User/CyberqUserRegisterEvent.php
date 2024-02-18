<?php

namespace App\Domains\User\Events\User;

use App\Domains\Admin\Traits\Auth\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CyberqUserRegisterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $display_name = $request->first_name.$request->last_name;
        $count = Str::length($display_name);
        dd($count);
        $exist_display_name = User::where('display_name',$request->display_name)->exist();
        if ($exist_display_name) {

        }

        while ($exist_display_name) {

        }
    }

}
