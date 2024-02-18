<?php

namespace App\Domains\Bundles\Models;

use INTCore\OneARTFoundation\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'heading', 'sub_heading', 'coupon_code',
        'background_color', 'button_color',
        'button_text_color', 'activation'
    ];


    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }
}
