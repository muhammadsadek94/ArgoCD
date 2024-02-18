<?php

namespace App\Domains\Favourite\Models;

use INTCore\OneARTFoundation\Model;

class Favourite extends Model
{
    protected $table = 'favourables';

    protected $fillable = ['user_id', 'favourable_id', 'favourable_type'];

    /**
     * Get the notifiable entity that the notification belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favouritable()
    {
        return $this->morphTo('favourables' , 'favourable_type', 'favourable_id');
    }

}
