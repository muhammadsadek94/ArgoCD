<?php

namespace App\Domains\Payments\Models;

use INTCore\OneARTFoundation\Model;
use App\Domains\Payments\Models\PaymentIntegration;
use App\Domains\User\Models\User;

class PaymentTransactionsHistory extends Model
{
    protected $table = 'payment_transactions_histories';
    protected $fillable = ['user_id', 'payment_integration_id', 'payable_id', 'payable_type', 'more_info', 'amount', 'order_id', 'status', 'pdf_url'];

    protected $casts = [
        'more_info' => 'object'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function integration()
    {
        return $this->belongsTo(PaymentIntegration::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }

}





