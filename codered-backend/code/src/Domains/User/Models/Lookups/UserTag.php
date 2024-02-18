<?php

namespace App\Domains\User\Models\Lookups;

use Framework\Events\UserCreated;
use INTCore\OneARTFoundation\Model;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\User;

class UserTag extends Model
{
    protected $fillable = ['name', 'activation','type'];

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function setImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_user_tag');
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }

}
