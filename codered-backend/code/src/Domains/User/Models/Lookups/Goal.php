<?php

namespace App\Domains\User\Models\Lookups;

use INTCore\OneARTFoundation\Model;

class Goal extends Model
{
    protected $fillable = ['name', 'activation'];

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }
}
