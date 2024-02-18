<?php

namespace App\Domains\Blog\Models;

use INTCore\OneARTFoundation\Model;

class ArticleCategory extends Model
{
    protected $fillable = ['name', 'color_hex', 'activation'];

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

}
