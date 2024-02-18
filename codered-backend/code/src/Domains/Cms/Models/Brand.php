<?php

namespace App\Domains\Cms\Models;

use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class Brand extends Model {
    protected $fillable = ['alt_text', 'image_id', 'brand_type'];
    public $incrementing = true;
    protected $keyType = "int";

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

}
