<?php

namespace App\Domains\User\Models\Instructor;

use App\Domains\Course\Models\Course;
use App\Domains\User\Models\Instructor\Payout;
use INTCore\OneARTFoundation\Model;

class PayoutRoyalty extends Model
{
    protected $fillable = [
        "payout_id","royalty","outstanding_advances","user_id","course_id","royalties_carried_out"
    ];
    
    protected $table = 'payout_royalties';


    public function Payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
