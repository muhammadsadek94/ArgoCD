<?php

namespace App\Domains\Notification\Broadcasting;


use Illuminate\Notifications\Notification;
use RuntimeException;

class DatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        return $notifiable->routeNotificationFor('database', $notification)->create(
            $this->buildPayload($notifiable, $notification)
        );
    }

    /**
     * Get the data for the notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return array
     */
    protected function getData($notifiable, Notification $notification) : array
    {
        if (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }
        return [];
    }

    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification)
    {
        return [
            'id' => $notification->id,
            'action_type' => $notification->action_type,
            'action_id' => $notification->action_id,
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
            'notifier_id' => $notification->notifier_id,
            "notifiable_type" => get_class($notifiable)
        ];
    }
}
