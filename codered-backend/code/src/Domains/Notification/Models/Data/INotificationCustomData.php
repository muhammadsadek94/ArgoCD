<?php

namespace App\Domains\Notification\Models\Data;

interface INotificationCustomData
{
    /**
     * CustomData constructor.
     * @param string $action_id
     * @param string $action_type
     */
    public function __construct(string $action_id, string $action_type);

    /**
     * @param string $key
     * @param string $value
     * @return CustomData
     */
    public function setData(string $key, $value) : CustomData;

    /**
     * @return array
     */
    public function getData() : array;
}
