<?php

namespace App\Domains\Enterprise\Models;

use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class
License extends Model
{
    protected $fillable = ['license', 'expired_at', 'license_type', 'duration', 'status', 'user_id', 'enterprise_id', 'subaccount_id','activation', 'package_id' , 'used_number'];

    public function users()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }
    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }
    public function scopeUsed($query)
    {
        $query->where('status', LicneseStatusType::USED);
    }

}
