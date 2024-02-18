<?php

namespace App\Domains\Geography\Models;

use App\Domains\Uploads\Jobs\UploadFileJob;
use Illuminate\Database\Eloquent\Model;
use App\Foundation\BaseModel;
class Country extends \INTCore\OneARTFoundation\Model
{
   protected $fillable = ["name_en", "name_ar", "iso", "phone_code", "number_allow_digit", "activation", "deleted_at",
       "image_id", "currency_name_ar", "currency_name_en", "currency_symbol", "nationality_en", "nationality_ar", "show_country",
        "show_nationality"];

    protected $attributes = [
        "show_country" => 1,                //not implemented
        "show_nationality" => 1,           //not implemented
        "nationality_en" => 0,            //not implemented
        "nationality_ar" => 0,           //not implemented
    ];

    public function Cities()
    {
        return $this->hasMany(City::class);
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

    public function setImageAttribute($image)
    {
        if (!$image) {
            return;
        }
        $image_instance = (new UploadFileJob($image))->handle();

        $this->attributes['image'] =  $image_instance->full_url;

    }

    public function scopeNationality($query)
    {
        return $query->where("show_nationality", 1);
    }

    public function scopeCountry($query)
    {
        return $query->where("show_country", 1);
    }
}
