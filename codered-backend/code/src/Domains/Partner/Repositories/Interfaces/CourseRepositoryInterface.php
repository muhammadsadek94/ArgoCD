<?php

namespace App\Domains\Partner\Repositories\Interfaces;

use App\Domains\Course\Models\Course;
use App\Domains\Partner\Models\Partner;
use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface extends RepositoryInterface
{
    /**
     * @param Request $request
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function courseFiltration(Request $request, Partner $partner , int $perPage = 20): LengthAwarePaginator;

    /**
     * @param string|null $partner_id
     * @return Course|null
     */
    public function findCourseById(string $partner_id, string $course_id): ?Model;
}
