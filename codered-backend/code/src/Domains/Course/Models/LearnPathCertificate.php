<?php

namespace App\Domains\Course\Models;

use App\Domains\Admin\Traits\Auth\User;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class LearnPathCertificate extends Model
{
    protected $fillable = ['user_id', 'learnpath_id', 'certificate_id', 'degree', 'certificate_number'];

    protected $with = ['learnpath', 'certificate'];

    public function learnPath()
    {
        return $this->belongsTo(LearnPathInfo::class, 'learnpath_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function certificate()
    {
        return $this->belongsTo(Upload::class);
    }
}
