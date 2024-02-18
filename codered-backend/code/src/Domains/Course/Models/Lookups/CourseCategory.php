<?php

namespace App\Domains\Course\Models\Lookups;

use INTCore\OneARTFoundation\Model;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Uploads\Models\Upload;

class CourseCategory extends Model
{
    protected $fillable = ['name', 'image_id', 'activation', 'label_color','icon_class_name', 'cat_parent_id', 'sort'];

    // protected $with = ['image'];

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function courses()
    {

        if(empty($this->cat_parent_id)) {
            return $this->hasMany(Course::class, 'course_category_id');
        }else {
            return $this->hasMany(Course::class, 'course_sub_category_id');
        }

    }

    public function paths() {
        return $this->hasMany(LearnPathInfo::class, 'category_id');
    }

    public function coursesAssignWithSub() {
        return $this->hasMany(Course::class, 'course_sub_category_id');
    }

    public function pathsAssignWithSub() {
        return $this->hasMany(LearnPathInfo::class, 'sub_category_id');
    }

    public function sub_categories() {
        return $this->hasMany(CourseCategory::class, 'cat_parent_id')->orderBy('sort', 'asc');
    }

    public function parent_category() {
        return $this->belongsTo(CourseCategory::class, 'cat_parent_id');
    }

    public function setImageIdAttribute(?string $image_id)
    {
        if(!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

    public function scopeParent($query) {
        return  $query->whereNull('cat_parent_id');
    }

    public function scopeChild($query) {
        return $query->whereNotNull('cat_parent_id');
    }

    public function isSub() {
        return $this->whereNotNull('cat_parent_id')->exists();
    }

}

