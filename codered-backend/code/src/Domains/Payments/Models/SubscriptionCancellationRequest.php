<?php

namespace App\Domains\Payments\Models;

use INTCore\OneARTFoundation\Model;
use App\Domains\Payments\Enum\SubscriptionCancellationRequestsType;
use App\Domains\Payments\Enum\SubscriptionCancellationRequestsStatus;
use App\Domains\User\Models\User;

class SubscriptionCancellationRequest extends Model
{
    protected $fillable = ['name', 'email', 'type', 'other', 'user_id', 'status'];

    protected $attributes = [
        'status' => SubscriptionCancellationRequestsStatus::NEW
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readable_type()
    {
        return SubscriptionCancellationRequestsType::getReadableType($this->type);
    }

    public function readable_status()
    {
        return SubscriptionCancellationRequestsStatus::getReadableStatus($this->status);
    }
}
