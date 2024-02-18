<?php

namespace App\Domains\Uploads\Models;

use INTCore\OneARTFoundation\Model;

class Upload extends Model
{
    const IN_USE = 1;
    const NOT_IN_USE = 0;

    protected $fillable = ["path", "size", "mime_type", 'in_use', 'container', 'full_url', 'is_default_profile_image'];


    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'id');
    }
}
