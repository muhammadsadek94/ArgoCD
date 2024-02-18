<?php

namespace App\Domains\User\Listeners\User\CyberQ;

use App\Domains\Admin\Traits\Auth\User;
use App\Domains\Course\Services\CyberQ\CyberQService;
use App\Domains\User\Events\User\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class RegisterUserCyberQ
{

    private $user;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(UserCreated $event)
    {
        /** @var User $user */
        $this->user = $event->user;
        // dd($this->user->first_name);
        $user_name = $this->user->first_name . $this->user->last_name;
        // $user_name = str_replace(' ', '', $user_name);
        // $user_name_count = Str::length($user_name);
        // if ($user_name_count >= 11) {
        //     $user_name = $this->user->first_name;
        //     $user_name = str_replace(' ', '', $user_name);
        //     $user_name_count = Str::length($user_name);
        //     if ($user_name_count >= 11) {
        //         $user_name = substr($user_name, 0, -6);
        //     }
        // }

        $cyberService = new CyberQService();
        $responce = $cyberService->authenticate($this->user, $user_name);

        // $uniqe_user_display_name = true;
        // while ($uniqe_user_display_name) {
        //     $random_num = mt_rand(1000000000000, 9999999999999);
        //     $num = substr($random_num, 0, 14 - $user_name_count);
        //     $user_display_name = $user_name . $num;
        //     while (User::where('display_name', $user_display_name)->exists()) {
        //         $random_num = mt_rand(1000000000000, 9999999999999);
        //         $num = substr($random_num, 0, 14 - $user_name_count);
        //         $user_display_name = $user_name . $num;
        //     }

        //     $cyberService = new CyberQService();
        //     $responce = $cyberService->registration($this->user, $user_display_name);
        //     if ($responce->data["success"]) {
        //         $this->user->display_name = $user_display_name;
        //         $this->user->update();
        //         $uniqe_user_display_name = false;
        //     }
        // }
    }
}
