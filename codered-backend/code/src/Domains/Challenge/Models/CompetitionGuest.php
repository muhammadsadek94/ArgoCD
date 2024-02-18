<?php

namespace App\Domains\Challenge\Models;

use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class CompetitionGuest extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'display_name','image_id'
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function flags()
    {
        return $this->hasMany(UserFlag::class, 'guest_id', 'id');
    }
}
