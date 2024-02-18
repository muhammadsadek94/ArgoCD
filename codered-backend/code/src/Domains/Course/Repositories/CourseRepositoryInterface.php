<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Models\Lookups\CourseCategory;
use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Foundation\Repositories\RepositoryInterface;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

//TODO: update methods
interface CourseRepositoryInterface extends RepositoryInterface
{
    public function getFeaturedCourses(int $limit = 30, array $with = [], ?User $user = null): Collection;

    public function bestSellersCategoryBased(int $limit = 30, array $with = [], ?User $user = null, ?CourseCategory $category = null): Collection;

    public function editorialPicksCategoryBased(int $limit = 30, array $with = [], ?User $user = null, ?CourseCategory $category = null): Collection;

    public function getFreeCourses(int $limit = 30, array $with = [], ?User $user = null) :Collection;

    public function getMicrodegrees(int $limit = 30, array $with = []): Collection;

    public function getMicrodegreesV2(Request $request, array $with = []): Collection;

    public function getRecentlyAddedCourses(int $limit = 30, array $with = []): Collection;

    public function getMostPopularCourses(int $limit = 30, array $with = []): Collection;

    public function getComingSoonCourses(int $limit = 30, array $with = [], ?User $user = null): Collection;

    public function getRecommendedCourses(?User $user, int $limit = 3);

    public function courseFiltration(Request $request, int $perPage = 20);

    public function courseFiltrationV2(Request $request, int $perPage = 18, ?User $user = null);

    public function getNotCompletedCourses(User $user): Collection;

    public function getCompletedCourses(User $user): Collection;

    public function getCoursesByCategoryId(string $course_category_id, $ignore_id = null, int $limit = 3, ?User $user = null);

    public function recommendCoursesDependingSubscriptions(User $user, $limit_free_courses = 8);

    public function getFreeCoursesForUnsubscribedUser(User $user);

    public function getCoursesWithLabs(int $limit = 30, array $with = []): Collection;

    //updates
    public function best_sellers();
    public function essentials(?User $user = null);
    public function editorial_picks();
    public function top_10_watched();
    public function hightest_rate(?User $user = null);
    public function courseMaxTime();
    public function top10(User $user, $random_user_catergory);

    public function getEssentialCourses(int $limit = 30, array $with = ['image', 'category', 'cover']): Collection;
}
