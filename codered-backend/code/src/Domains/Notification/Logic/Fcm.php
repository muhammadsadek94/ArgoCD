<?php

namespace App\Domains\Notification\Logic;


use App\Domains\Notification\Models\Data\INotificationCustomData;

class Fcm
{

    private $message;
    private $custom_data;

    public function __construct()
    {
        //
    }

    /**
     * @return FcmMessage
     */
    public function send() :FcmMessage
    {
        $notification = new FcmMessage();
        $notification->setSettings($this->message)
            ->setData($this->custom_data)
            ->priority(FcmMessage::PRIORITY_HIGH);
        return $notification;
    }

    /**
     * @param array $message
     * @param string $icon
     * @param string|null $click_action
     * @param string $sound
     * @return Fcm
     */
    public function prepare(array $message, string $icon = '', string $click_action = null, string $sound = 'default') :Fcm
    {
        $this->message = [
            "icon"         => $icon,
            "click_action" => $click_action,
            'sound'        => $sound
        ];

        return $this;
    }

    /**
     * @param INotificationCustomData $custom_data
     * @return $this
     */
    public function addCustoms(INotificationCustomData $custom_data) :Fcm
    {
        $this->custom_data = $custom_data->getData();

        return $this;
    }

}
