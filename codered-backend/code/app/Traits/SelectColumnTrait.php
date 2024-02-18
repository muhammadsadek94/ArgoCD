<?php

namespace Framework\Traits;

trait SelectColumnTrait
{
    public static $imageColumnsInline = 'image:id,path,full_url,mime_type';
    public static $coverColumnsInline = 'cover:id,path,full_url,mime_type';
    public static $menuCoverColumnsInline = 'cover:id,path,full_url,mime_type';
    public static $categoryColumnsInline = 'category:id,name,image_id,activation,label_color,icon_class_name,cat_parent_id,sort';
    public static $subCategoryColumnsInline = 'sub:id,name,image_id,activation,label_color,icon_class_name,cat_parent_id,sort';
    public static $reviewsColumnsInline = 'reviews:id,course_id,rate,user_id,activation';
    public static $learnPathsCoursesColumnsInline = 'courses:id,course_id,learn_path_id';


    public static $completedCoursesColumns = ['completed_courses.id', 'user_id', 'completed_courses.course_id'];
    public static $coursesColumns = ['courses.id', 'courses.name', 'courses.brief', 'courses.level', 'courses.timing', 'courses.course_sub_category_id', 'courses.course_category_id', 'courses.course_type', 'courses.image_id', 'courses.slug_url', 'courses.is_free', 'courses.price', 'courses.discount_price', 'courses.is_featured', 'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment', 'courses.agg_lessons', 'courses.agg_count_course_chapters'];
    public static $categoryColumns = ['id', 'name', 'image_id', 'activation', 'label_color', 'icon_class_name', 'cat_parent_id', 'sort'];
    public static $completedPercentageLoadColumns = ['id', 'user_id', 'course_id', 'completed_percentage', 'is_finished'];
    public static $learnPathBasicColumns = ['id', 'name', 'activation', 'slug_url', 'category_id', 'sub_category_id'];
    public static $learnPathCoursesLoad = ['courses.id', 'timing', 'activation'];
    public static $userActiveSubscriptionsColumns = ['id', 'status', 'expired_at', 'subscription_id', 'user_id', 'package_id', 'is_installment', 'paid_installment_count'];
}

