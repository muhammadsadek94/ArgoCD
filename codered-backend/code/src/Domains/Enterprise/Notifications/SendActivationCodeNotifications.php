<?php

namespace App\Domains\Enterprise\Notifications;

use App\Domains\User\Mails\SendOTPMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendActivationCodeNotifications extends Notification implements ShouldQueue
{
    use Queueable;

    public $code;

    /**
     * Create a new notification instance.
     *
     * @param mixed $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if(!empty($notifiable->email))
            return ["mail"];
        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return SendOTPMail
     */
    public function toMail($notifiable)
    {
        return (new SendOTPMail("Activation code", $this->code, $notifiable->user))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
