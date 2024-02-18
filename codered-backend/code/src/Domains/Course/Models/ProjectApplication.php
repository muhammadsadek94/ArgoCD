<?php

namespace App\Domains\Course\Models;

use App\Domains\Comment\Models\Comment;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Model;

class ProjectApplication extends Model
{
    protected $fillable = ['user_id', 'lesson_id', 'course_id', 'url', 'activation', 'status'];

    /**
     * Get the user associated with the ProjectApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


        /**
     * Get the user associated with the ProjectApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }



        /**
     * Get the user associated with the ProjectApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }


    // public function comments()
    // {
    //     return $this->morphTo(Lesson::class);
    // }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'entity','entity_type','entity_id')->orderBy('created_at');
    }

}
