<?php

namespace App\Domains\User\Models\Instructor;

use App\Domains\Course\Models\Course;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Enum\PayoutStatus;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class Payout extends Model
{
    protected $fillable = ['name', 'period', 'status', 'amount', 'attachment_id', 'user_id', 'year', 'quarter', 'secondary_id', 'royalties_courses','royalties_bundles'
    ,'course_id','royalty','royalties_carried_out','outstanding_advances', 'start_date', 'end_date', 'type'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id' , 'id');
    }

    public function attachment()
    {
        return $this->belongsTo(Upload::class, 'attachment_id');
    }

    public function scopeNotRejected($query) {
        return $query->whereIn('status', [
            PayoutStatus::PAID,
            PayoutStatus::PENDING,
            PayoutStatus::APPROVED,
        ]);
    }

    public function scopePending($query) {
        return $query->whereIn('status', [
            PayoutStatus::PENDING,
        ]);
    }

    public function scopeApproved($query) {
        return $query->whereIn('status', [
            PayoutStatus::APPROVED,
        ]);
    }

    public function scopePaid($query) {
        return $query->whereIn('status', [
            PayoutStatus::PAID,
        ]);
    }

    public function scopePendingAndApproved($query) {
        return $query->whereIn('status', [
            PayoutStatus::APPROVED,
            PayoutStatus::PENDING,
        ]);
    }

    public function scopeHistory($query) {
        return $query->whereIn('status', [
            PayoutStatus::DISAPPROVE,
            PayoutStatus::PAID,
        ]);
    }


    public function royalties()
    {
        return $this->hasMany(PayoutRoyalty::class, 'payout_id',"id");
    }



}

