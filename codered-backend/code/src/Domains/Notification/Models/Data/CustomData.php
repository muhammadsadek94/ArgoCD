<?php


namespace App\Domains\Notification\Models\Data;


class CustomData implements INotificationCustomData
{

    private $data = [];
    private $action_id;
    private $action_type;

    /**
     * CustomData constructor.
     * @param string $action_id
     * @param string $action_type
     */
    public function __construct(string $action_id, string $action_type)
    {
        $this->action_id = $action_id;
        $this->action_type = $action_type;
    }

    /**
     * @param $key
     * @param $value
     * @return CustomData
     */
    public function setData($key, $value) :CustomData
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getData() :array
    {
        $init = ['action_id' => $this->action_id, 'action_type' => $this->action_type];

        return array_merge($this->data, $init);
    }


}
