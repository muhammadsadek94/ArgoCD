<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class JobRole extends Model
{
    protected $fillable = ['name', 'activation'];

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }


}
