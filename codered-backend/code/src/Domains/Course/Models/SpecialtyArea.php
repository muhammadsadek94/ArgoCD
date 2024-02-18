<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class SpecialtyArea extends Model
{
    protected $fillable = [ 'name','activation'];

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

}
