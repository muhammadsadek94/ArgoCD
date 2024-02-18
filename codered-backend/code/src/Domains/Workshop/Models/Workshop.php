<?php

namespace App\Domains\Workshop\Models;

use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\User;
use Carbon\Carbon;
use DateTime;
use INTCore\OneARTFoundation\Model;

class Workshop extends Model
{
    protected $fillable = ['title' ,'date', 'time', 'user_id', 'description', 'link', 'activation', 'type', 'image_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getTimeConverted($time){
        return Carbon::parse($time)->format('g:i A');
    }

    public function getDateConverted($date){
        $day = Carbon::parse($date)->format('dS');
        $month = Carbon::parse($date)->format('M');
        $year = Carbon::parse($date)->format('Y');
        $full_date = $day . ' of ' . $month .' '. $year ;
        return $full_date;
    }

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

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
