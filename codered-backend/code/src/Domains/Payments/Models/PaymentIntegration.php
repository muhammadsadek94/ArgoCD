<?php

namespace App\Domains\Payments\Models;

use INTCore\OneARTFoundation\Model;

class PaymentIntegration extends Model
{
    protected $fillable = ['name', 'product_id', 'payable_id', 'payable_type'];

    public function payable()
    {
        return $this->morphTo();
    }
}
