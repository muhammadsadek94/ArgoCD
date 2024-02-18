<?php

namespace App\Domains\Admin\Models;

use INTCore\OneARTFoundation\Model;

class Module extends Model
{
    protected $fillable = ["name"];

    public function permissions()
    {
        return $this->hasMany(Ability::class, "module_id", "id");
    }
}
