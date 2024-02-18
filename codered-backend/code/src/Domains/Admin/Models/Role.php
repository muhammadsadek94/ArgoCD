<?php

namespace App\Domains\Admin\Models;

use INTCore\OneARTFoundation\Model;

class Role extends Model
{
    protected $fillable = ["name", "label", 'is_super_admin'];

    protected $attributes = [
        'is_super_admin' => 0
    ];

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, "ability_role", "role_id", "ability_id");
    }

    public function allow($ability)
    {
        return $this->abilities()->sync($ability);
    }

}
