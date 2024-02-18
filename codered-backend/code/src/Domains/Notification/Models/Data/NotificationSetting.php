<?php


namespace App\Domains\Notification\Models\Data;


class NotificationSetting
{

    private $icon = null;
    private $sound = 'default';
    private $click_action = null;

    /**
     * @param null $icon
     * @return NotificationSetting
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $sound
     * @return NotificationSetting
     */
    public function setSound(string $sound) :NotificationSetting
    {
        $this->sound = $sound;
        return $this;
    }

    /**
     * @param null $click_action
     * @return NotificationSetting
     */
    public function setClickAction($click_action)
    {
        $this->click_action = $click_action;
        return $this;
    }


    public function getSettings() {
        return [
            'sound'        => $this->sound, // Optional
            'icon'         => $this->icon, // Optional
            'click_action' => $this->click_action // Optional
        ];
    }

}
