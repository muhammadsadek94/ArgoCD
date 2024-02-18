<?php

namespace App\Domains\Blog\Models;

use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class Quote extends Model
{
    protected $fillable = ['quote', 'author_name', 'author_position', 'author_image_id', 'activation'];


    public function setAuthorImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['author_image_id'] = $image_id;
        }

        return $this->attributes['author_image_id'] = null;
    }

    public function author_image()
    {
        return $this->hasOne(Upload::class, 'id', 'author_image_id');
    }

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

}
