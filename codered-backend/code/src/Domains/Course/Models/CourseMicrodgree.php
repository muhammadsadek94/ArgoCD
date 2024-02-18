<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;
use App\Domains\Uploads\Models\Upload;

class CourseMicrodgree extends Model
{
    protected $table = 'course_microdegrees';

    protected  $fillable = [
        'course_id', 'prerequisites', 'average_salary', 'estimated_time',
        'faq', 'syllabus_url', 'slack_url', 'key_features', 'skills_covered', 'project'
    ];


    protected $casts = [
        'faq' => 'array',
        'key_features' => 'array',
        'skills_covered' => 'array',
        'project' => 'array',
    ];


    public function getFaqAttribute($faq)
    {
        if (is_string($faq) || $faq == '[]') {
            return json_decode($faq);
        }

        return $faq;
    }


    public function getKeyFeaturesAttribute($key_features)
    {
        if (is_string($key_features) || $key_features == '[]') {
            return json_decode($key_features);
        }

        return $key_features;
    }

    public function getSkillsCoveredAttribute($skills_covered)
    {
        if (is_string($skills_covered) || $skills_covered == '[]') {
            return json_decode($skills_covered);
        }

        return $skills_covered;
    }

    public function getProjectAttribute($project)
    {
        if (is_string($project) || $project == '[]') {
            return json_decode($project);
        }

        return $project;
    }
}
