<?php

namespace App\Domains\Course\Models;

use INTCore\OneARTFoundation\Model;

class ChapterKnowledgeAssessmentTags extends Model
{

    protected $keyType = "int";
    protected $table = 'chapter_knowledge_assessment_tags';
    protected $fillable = [
        'course_id', 'chapter_id','competency_id', 'speciality_area_id','ksa_id',
    ];

    public static function boot()
    {
        static::bootTraits();
    }
}
