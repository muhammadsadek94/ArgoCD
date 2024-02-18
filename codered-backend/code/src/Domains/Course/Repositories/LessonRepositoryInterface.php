<?php

namespace App\Domains\Course\Repositories;

use App\Foundation\Repositories\RepositoryInterface;

//TODO: extract methods
interface LessonRepositoryInterface extends RepositoryInterface
{
    public function checkBookAccess($user_id ,$book_id);
    public function setBookAccess($user_id ,$book_id,$code);

}
