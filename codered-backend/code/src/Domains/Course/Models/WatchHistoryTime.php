<?php

namespace App\Domains\Course\Models;

use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class WatchHistoryTime extends Model
{
    public $incrementing = true;

    protected $keyType = "int";

    protected $fillable = ['lesson_id', 'user_id', 'course_id','watched_time','instructor_id','status','subscription_type','course_type', 'created_at'];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        static::bootTraits();
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
