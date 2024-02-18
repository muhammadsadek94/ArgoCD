<?php

namespace App\Domains\Course\Models;

use App\Domains\Course\Enum\LessonType;
use App\Domains\UserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use INTCore\OneARTFoundation\Model;
use Illuminate\Database\Eloquent\Builder;

class Chapter extends Model
{

    use Loggable;

    protected $fillable = ['name', 'activation', 'course_id', 'sort', 'drip_time', 'description', 'course_objective', 'agg_lessons'];

    protected $casts = [
        'agg_lessons'   => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            return $builder->orderBy('chapters.sort', 'asc');
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->where('type', '!=', LessonType::CHECKPOINT);
    }

    /**
     * @return HasMany
     */
    public function chapter_knowledge_assessment_tags(): HasMany
    {
        return $this->hasMany(ChapterKnowledgeAssessmentTags::class, 'chapter_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }
}
