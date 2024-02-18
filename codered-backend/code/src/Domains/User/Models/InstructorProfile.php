<?php

namespace App\Domains\User\Models;

use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class InstructorProfile extends Model
{
    protected $fillable = [
        'user_id', 'current_employer', 'designation',
        'linkedin_url', 'github_url', 'blog_url', 'article_url',
        'facebook_url', 'instagram_url', 'twitter_url',
        'years_experience', 'profile_summary', 'cv_id',
        'have_courses', 'course_information', 'interested_video', 'interested_assessments',
        'interested_written_materials',
        'have_trending_course', 'trending_course_description', 'trending_course_topic',
        'trending_course_target_audience',
        'video_sample_id',
        'bank_name', 'account_number', 'iban', 'swift_code',
        'commission_percentage', 'job',
        'billing_address',
        'payee_name',
        'payee_bank_country',
        'payee_branch_name',
        'branch_code',
        'intermediary_bank',
        'routing_number',
        'payee_bank_for_tt',
    ];

    public function cv()
    {
        return $this->belongsTo(Upload::class, 'cv_id', 'id');
    }

    public function video_sample()
    {
        return $this->belongsTo(Upload::class, 'video_sample_id', 'id');
    }


}
