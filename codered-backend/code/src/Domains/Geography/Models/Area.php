<?php

namespace App\Domains\Geography\Models;

use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class Area extends Model
{
    protected $fillable = ["name_en", "name_ar", "activation", "city_id", "image_id"];
    protected $hidden = ['image_id'];

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'area_interest_user', 'area_id', "user_id");
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }
}
