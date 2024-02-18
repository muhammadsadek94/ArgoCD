<?php

namespace App\Domains\Voucher\Models;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\UserActivity\Traits\Loggable;
use App\Domains\Voucher\Enum\VoucherUsageStatus;
use INTCore\OneARTFoundation\Model;
use App\Domains\User\Models\User;

/**
 * @property mixed               id
 * @property string              name
 * @property string              voucher
 * @property boolean             is_used
 * @property string              expired_at
 * @property string              payable_type
 * @property string              payable_id
 * @property array               tags
 * @property integer             days
 * @property PackageSubscription payable
 */
class Voucher extends Model
{
    use Loggable;

    protected $fillable = ['name', 'voucher', 'is_used', 'expired_at', 'payable_type', 'payable_id', 'tags', 'days','access_type', 'user_id'];

    protected $attributes = [
        'is_used'      => VoucherUsageStatus::PENDING,
        'payable_type' => PackageSubscription::class,
        'days'         => 0
    ];

    protected $casts = [
        'tags' => 'array'
    ];

//    protected $hidden = ['days'];
//    protected $guarded = ['days'];

    public function payable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * check if voucher is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->is_used == VoucherUsageStatus::PENDING && $this->expired_at > now()->format('Y-m-d');
    }
}
