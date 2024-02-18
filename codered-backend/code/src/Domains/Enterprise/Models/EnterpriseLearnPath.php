<?php

namespace App\Domains\Enterprise\Models;

use App\Domains\Payments\Models\PackageSubscription;
use INTCore\OneARTFoundation\Model;

class EnterpriseLearnPath extends Model
{
    protected $fillable = ['enterprise_id', 'package_id','type', 'deadline-type' , 'expiration_date' ,
        'expiration_days','activation'];

    public function package()
    {
    return $this->belongsTo(PackageSubscription::class, 'package_id', 'id');

    }


    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }


}
