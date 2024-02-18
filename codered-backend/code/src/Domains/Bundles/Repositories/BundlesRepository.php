<?php

namespace App\Domains\Bundles\Repositories;

use App\Domains\Bundles\Enum\BundleType;
use App\Domains\Bundles\Enum\DisplayStatus;
use App\Domains\Bundles\Models\CourseBundle;
use App\Domains\Bundles\Repositories\Interfaces\BundlesRepositoryInterface;
use App\Domains\Course\Models\Course;
use App\Foundation\Repositories\Repository;
use Illuminate\Http\Request;

class BundlesRepository extends Repository implements BundlesRepositoryInterface
{
    public function __construct(CourseBundle $model)
    {
        parent::__construct($model);
    }

    /**
     * get bundle by id
     *
     * @param string $bundle_id
     * @return mixed
     */

    public function getBundleById(string $bundle_id)
    {
        $bundle = $this->getModel()->active()->find($bundle_id);

        return $bundle;

    }

    /**
     * get featured bundles that's marked as featured from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getFeaturedBundles(int $limit = 5)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('display_status', DisplayStatus::FEATURED)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    /**
     * get featured bundles that's marked as featured from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getFeaturedBundlesV2(Request $request)
    {
        $query = $this->getModel()->newQuery();

        // $courseIds = Course::where('courses.name', 'LIKE', "%{$request->keyword}%")->pluck('id')->toArray();


        $query = $query->when($request->keyword, function($query) use ($request) {
            return $query->where('name', 'like', "%{$request->keyword}%")
            ->orWhere('description', "LIKE", "%{$request->keyword}%");
        });

        // $courseIds = $query->pluck('access_id')->toArray();

        // $list = [];
        // foreach($courseIds as $value) {
        //     foreach($value as $row) {
        //         array_push($list, $row);
        //     }
        // }

        // $query->when(count($list) > 0, function($q) use ($request, $list) {
        //     return Course::whereIn('id', $list)
        //         ->where('courses.name', 'LIKE', "%{$request->keyword}%");
        // });


        return $query->where('display_status', DisplayStatus::FEATURED)
            ->latest('created_at')
            ->active()
            ->get();
    }

    /**
     * get skill based bundles that's marked as skill-based from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getSkillBasedBundles(int $limit = 5)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('bundle_type', BundleType::SKILL)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    /**
     * get job based bundle details that's marked as skill_based from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getJobBasedBundles(int $limit = 5)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('bundle_type', BundleType::JOB)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    /**
     * get category based bundle details that's marked as category_based from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getCategoryBasedBundles(int $limit = 5)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('bundle_type', BundleType::CATEGORY)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    /**
     * get bestseller bundle details that's marked as bestseller from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getBestsellerBundles(int $limit = 2)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('is_bestseller', 1)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    /**
     * get new arrival bundle details that's marked as new_arrival from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getNewArrivalBundles(int $limit = 2)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('is_new_arrival', 1)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    /**
     * get spotlight bundle details that's marked as is_bundlespotlight=true from admin panel
     *
     * @param int $limit
     * @return mixed
     */

    public function getSpotlightBundles(int $limit = 2)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('is_bundle_spotlight', 1)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    /**
     * fetch the last bundle which was created and in front-end it would be shown as bundle of week
     *
     * @param int $limit
     * @return mixed
     */
    public function getBundleOfDayBundles()
    {

        return $this->getModel()
            ->active()
            ->latest('created_at')
            ->first();


    }

    /**
     * get all the bundles which has a particular course
     *
     * @return mixed
     */
    public function getBundleByCourseId(string $course_id)
    {


        $query = $this->getModel()->newQuery();
        return $query->whereRaw('json_contains(access_id, \'["' . $course_id . '"]\')')
            ->latest('created_at')
            ->get();


    }

    public function bundleFiltration(Request $request, int $perPage = 20)
    {
        $search = $this->getModel()->newQuery();
        $search = $search->when($request->search, function ($query) use ($request) {
            $query->where('name', "LIKE", "%{$request->search}%")
                ->orWhere('description', "LIKE", "%{$request->search}%");
        });
        return $search->get();
    }

}
