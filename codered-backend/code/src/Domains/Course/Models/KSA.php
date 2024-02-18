<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class KSA extends Model
{
    protected $table = 'ksas';
    protected $fillable = ['name', 'activation'];

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }
}
