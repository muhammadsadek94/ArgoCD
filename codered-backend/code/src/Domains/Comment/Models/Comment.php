<?php

namespace App\Domains\Comment\Models;

use INTCore\OneARTFoundation\Model;

class Comment extends Model
{
    protected $fillable =['comment' ,'owner_id','owner_type','entity_id','entity_type' , 'activation'];


    public function owner (){
     return   $this->morphTo('owner','owner_type','owner_id');
    }
}
