<?php

namespace App\Domains\Bundles\Repositories\Interfaces;

use App\Domains\Bundles\Models\CourseBundle;
use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface BundlesRepositoryInterface extends RepositoryInterface
{


    /**
     * get bundle by id 
     * @param string $bundle_id
     * @return mixed
     */
    public function getBundleById(string $bundle_id);


     /**
     * get featured bundles that's marked as featured from admin panel
     *
     * @param int     $limit
     * @return mixed
     */
    public function getFeaturedBundles(int $limit = 5);


     /**
     * get skill based bundle details that's marked as skill_based from admin panel
     *
     * @param int     $limit
     * @return mixed
     */
    public function getSkillBasedBundles(int $limit = 5);


    /**
     * get job based bundle details that's marked as skill_based from admin panel
     *
     * @param int     $limit
     * @return mixed
     */
    public function getJobBasedBundles(int $limit = 5);

    /**
     * get category based bundle details that's marked as category_based from admin panel
     *
     * @param int     $limit
     * @return mixed
     */
    public function getCategoryBasedBundles(int $limit = 5);


    /**
     * get bestseller bundle details that's marked as bestseller from admin panel
     *
     * @return mixed
     */
    public function getBestsellerBundles(int $limit = 2);

    /**
     * get new arrival bundle details that's marked as new_arrival from admin panel
     *
     * @return mixed
     */
    public function getNewArrivalBundles(int $limit = 2);


     /**
     * get spotlight bundle details that's marked as is_bundlespotlight=true from admin panel
     *
     * @return mixed
     */
    public function getSpotlightBundles(int $limit = 2);

    

    /**
     * get any 1 bundle details that has deal_end_date=today
     *
     * @return mixed
     */
    public function getBundleOfDayBundles();



    /**
     * get all the bundles which has a particular course
     *
     * @return mixed
     */
    public function getBundleByCourseId(string $course_id);

    /**
     * return bundles based on search name
     *
     * @return mixed
     */
    public function bundleFiltration(Request $request, int $perPage = 20);



}
