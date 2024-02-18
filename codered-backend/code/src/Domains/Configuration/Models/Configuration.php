<?php

namespace App\Domains\Configuration\Models;

use Setting;

class Configuration extends Setting
{
    const FILLABLE = [
        'search_max_price','search_min_price','loan_interest_rate',  'promote_property_price', 'promote_property_period'
    ];

    public static function set($key, $value = null)
    {
        if(is_string($key)) self::set($key, $value);

        if(is_array($key)) {
            $settings = $key;

            foreach($settings as $key => $setting) {
                parent::set($key, $setting);
            }
        }

        self::save();

    }

    public static function get($key = null)
    {

        if($key != null) return parent::get($key);

        return parent::get(self::FILLABLE);

    }
}
