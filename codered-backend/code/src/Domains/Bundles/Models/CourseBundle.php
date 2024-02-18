<?php

namespace App\Domains\Bundles\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Uploads\Models\Upload;
use INTCore\OneARTFoundation\Model;

class CourseBundle extends Model
{

    protected $fillable = [
        'name', 'description', 'image_id', 'features', 'display_status', 'jobs',
        'topics', 'certifications', 'bundle_type','payment_title','price',
        'sale_price','price_period','bundle_url','access_pass_url','package_id',
        'price_features','activation','is_bestseller','is_new_arrival','learn_features','bundle_spotlight','deal_end_date','cover_image_id','bestseller_brief','newarrival_brief','is_bundle_spotlight','access_type', 'access_id'
    ];

    protected $casts = [
        'features'              => 'array',
        'jobs'                  => 'array',
        'topics'                => 'array',
        'certifications'        => 'array',
        'price_features'         => 'array',
        'is_bestseller'         => 'boolean',
        'is_new_arrival'        => 'boolean',
        'learn_features'        => 'array',
        'is_bundle_spotlight'   => 'boolean',
        'bundle_spotlight'      => 'array',
        'access_id'             => 'array',
    ];


    public function setImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function image()
    {
        return $this->hasOne(Upload::class, 'id', 'image_id');
    }

    public function setCoverImageIdAttribute(?string $image_id)
    {
        if (!empty($image_id)) {
            return $this->attributes['cover_image_id'] = $image_id;
        }

        return $this->attributes['cover_image_id'] = null;
    }

    public function cover_image()
    {
        return $this->hasOne(Upload::class, 'id', 'cover_image_id');
    }

    public function scopeActive($query)
    {
        $query->where('activation', 1);
    }

    public function scopeCoursesBundle($query)
    {
        return $query->where('course_id', AccessType::COURSES);
    }


}
