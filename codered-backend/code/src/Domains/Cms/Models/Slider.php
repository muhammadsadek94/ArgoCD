<?php

namespace App\Domains\Cms\Models;

use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class Slider extends Model {
    protected $fillable = ['title', 'sub_title', 'description', 'image_id', 'title_color', 'sub_title_color', 'description_color', 'button_txt', 'button_target_url'];

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

    public function brands(){
        return $this->belongsToMany(Brand::class, 'brand_slider');
    }

}
