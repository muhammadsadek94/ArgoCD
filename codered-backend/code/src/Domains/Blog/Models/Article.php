<?php

namespace App\Domains\Blog\Models;

use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;
use App\Domains\Admin\Models\Admin;

class Article extends Model
{
    protected $fillable = [
        'name', 'description', 'content', 'tags',
        'is_featured', 'activation',
        'article_category_id', 'image_id', 'internal_image_id',
        'views','admin_id'
    ];

    protected $casts = [
        'tags'        => 'array',
        'is_featured' => 'boolean',
        'activation'  => 'boolean',
    ];

    public function setImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function setInternalImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['internal_image_id'] = $image_id;
        }

        return $this->attributes['internal_image_id'] = null;
    }

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function internal_image()
    {
        return $this->hasOne(Upload::class, 'id', 'internal_image_id');
    }

    public function category()
    {
        return $this->hasOne(ArticleCategory::class, 'id', 'article_category_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }


}
