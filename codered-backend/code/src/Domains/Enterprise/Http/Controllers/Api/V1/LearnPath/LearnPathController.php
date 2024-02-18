<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\LearnPath;

use App\Domains\Enterprise\Features\Api\V1\LearnPath\AssignCoursesToLearnPathsFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\AssignUsersFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\CreateLearnPathFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\EditLearnPathBasicDataFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\GetFillterCourseFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\GetFillterForCourseFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\GetFilltersForCoursesFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\GetFillterTagsFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\GetFilterLearnPathsFeature;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\GetLearnPathsFeatureById;
use App\Domains\Enterprise\Features\Api\V1\LearnPath\RemoveAssignUserFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class LearnPathController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(GetFilterLearnPathsFeature::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->serve(CreateLearnPathFeature::class);

    }

    public function getFilterCourse(){
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->serve(GetLearnPathsFeatureById::class);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->serve(EditLearnPathBasicDataFeature::class);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Assign course to learnpaths.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignCourse(Request $request)
    {
        return $this->serve(AssignCoursesToLearnPathsFeature::class);
    }


    public function assignUser(Request $request)
    {
        return $this->serve(AssignUsersFeature::class);
    }
    public function removeAssignUser(Request $request)
    {
        return $this->serve(RemoveAssignUserFeature::class);
    }

    public function filterCourse(Request $request)
    {
        return $this->serve(GetFillterCourseFeature::class);

    }


    public function filterTags(Request $request)
    {
        return $this->serve(GetFillterTagsFeature::class);

    }

    public function filtration(Request $request)
    {
        return $this->serve(GetFillterForCourseFeature::class);

    }


}
