<?php

namespace App\Domains\Course\Repositories;

use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\BookAccess;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;

class LessonRepository extends Repository implements LessonRepositoryInterface
{
    public function __construct(Lesson $model) { parent::__construct($model); }

    public function checkBookAccess($user_id , $book_id)
    {
        $access = BookAccess::where('user_id',$user_id)->where('book_id',$book_id)->first();
        return $access;
    }
    public function setBookAccess($user_id , $book_id,$code)
    {
        $access = new BookAccess();
        $access->user_id = $user_id ;
        $access->book_id = $book_id ;
        $access->code = $code ;
        $access->save();
        return $access;
    }
}
