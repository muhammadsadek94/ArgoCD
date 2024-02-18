<?php

namespace App\Domains\Enterprise\Models;

use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class EnterpriseInfo extends Model
{
    protected $fillable = ['licenses_reuse_number', 'enterprise_id'];

    public function enterprise()
    {
        return $this->hasOne(User::class, 'id', 'enterprise_id');
    }

}
