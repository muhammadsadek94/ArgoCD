<?php

namespace App\Domains\Blog\Models;

use INTCore\OneARTFoundation\Model;

class ArticleLikes extends Model
{
     protected $fillable = ['article_id', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
