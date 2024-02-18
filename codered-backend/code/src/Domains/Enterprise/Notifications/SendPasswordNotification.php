<?php

namespace App\Domains\Enterprise\Notifications;

use App\Domains\User\Mails\SendInstructorPasswordMail;
use App\Domains\User\Mails\SendPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Mail;
class SendPasswordNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $password;

    /**
     * Create a new notification instance.
     *
     * @param $password
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return
     */
    public function toMail($notifiable)
    {
        $subject = "Your EC-Council Learning Account Details";
        $head_message = 'We have created a new EC-Council Learning account as per your recent request.';
        Mail::to($notifiable->email)->send(new SendPasswordMail($subject, $this->password, $notifiable, $head_message));

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
