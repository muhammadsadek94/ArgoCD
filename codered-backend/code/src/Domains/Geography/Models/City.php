<?php

namespace App\Domains\Geography\Models;

use App\Foundation\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static active()
 */
class City extends \INTCore\OneARTFoundation\Model
{
    protected $fillable = ["name_en", "name_ar", "activation", "country_id", "deleted_at"];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

}
