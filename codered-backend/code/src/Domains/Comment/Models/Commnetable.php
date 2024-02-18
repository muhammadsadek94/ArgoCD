<?php

namespace App\Domains\Comment\Models;

use INTCore\OneARTFoundation\Model;

class Commnetable extends Model
{
    protected $fillable = ['comment_id', 'commentable_id', 'commentable_type', 'model_id', 'model_type', 'show_to_id', 'show_to_type'];

    public function owner (){
        $this->morphTo('owner');
    }
}
