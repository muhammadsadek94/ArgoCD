<?php

namespace App\Domains\Admin\Models;

use INTCore\OneARTFoundation\Model;

class Ability extends Model
{
    protected $fillable = ["ability", "name", "module_id"];

    public function roles()
    {
        return $this->belongsToMany(Role::class, "ability_role", "ability_id", "role_id");
    }

    public function module()
    {
        return $this->belongsTo(Module::class, "module_id", "id");
    }
}
