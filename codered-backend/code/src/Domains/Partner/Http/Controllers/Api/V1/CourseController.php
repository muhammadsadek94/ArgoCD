<?php

namespace App\Domains\Partner\Http\Controllers\Api\V1;

use App\Domains\Partner\Features\Api\V1\FilterCourseFeature;
use App\Domains\Partner\Features\Api\V1\FilterCourseFilterationDataFeature;
use App\Domains\Partner\Features\Api\V1\GetCourseDetailsFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(FilterCourseFeature::class);
    }



    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function GetFilterationData()
    {
        return $this->serve(FilterCourseFilterationDataFeature::class);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->serve(GetCourseDetailsFeature::class);
    }
}
