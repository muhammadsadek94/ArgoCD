<?php

namespace App\Domains\User\Notifications;

use App\Domains\User\Mails\SendOTPMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UpdateEmailOTPNotifications extends Notification implements ShouldQueue
{
    use Queueable;

    public $code;
    public $email;

    /**
     * Create a new notification instance.
     *
     * @param string $code
     * @param string $email
     */
    public function __construct($code, $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["mail"];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return SendOTPMail
     */
    public function toMail($notifiable)
    {
        return (new SendOTPMail("Change your emaill address code", $this->code, $notifiable->user))->to($this->email);
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
