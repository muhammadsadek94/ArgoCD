<?php

namespace App\Domains\Enterprise\Notifications;

use App\Domains\Enterprise\Mails\LearnpathAssignedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Mail;
class LearnpathAssignedToUserNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $package_subscription;

    /**
     * Create a new notification instance.
     *
     * @param $password
     */
    public function __construct($package_subscription)
    {
        $this->package_subscription = $package_subscription;
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
        $subject = "You have been assigned to a new learning path";
        $head_message = 'You have been assigned to a new learning path';
        return (new LearnpathAssignedEmail($subject, $this->package_subscription, $notifiable, $head_message))->to($notifiable->email);
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
