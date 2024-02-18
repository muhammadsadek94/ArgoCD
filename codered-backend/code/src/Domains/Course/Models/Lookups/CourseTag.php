<?php

namespace App\Domains\Course\Models\Lookups;

use App\Domains\Course\Models\Course;
use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class CourseTag extends Model
{
    protected $fillable = ['name', 'activation', 'image_id', 'course_category_id', 'is_featured'];

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id', 'id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function setImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function scopeActive($query)
    {
        return $query->where('activation', 1);
    }
    public function scopeFeature($query)
    {
        return $query->where('is_featured', 1);
    }
}
