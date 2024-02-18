<?php


namespace App\Domains\Course\Http\Controllers\Api\V2\User;

use App\Domains\Course\Features\Api\V2\User\GetInternalCourseFeature;
use App\Domains\Course\Features\Api\V2\User\GetProPageFeature;
use App\Domains\Course\Features\Api\V2\User\AutocompleteFiltrationFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Course\Features\Api\V2\User\CourseFiltrationFeature;
use App\Domains\Course\Features\Api\V2\User\GetCourseByIdFeature;
use App\Domains\Course\Features\Api\V2\User\EnrollInCourseFeature;
use App\Domains\Course\Features\Api\V2\User\SubmitCourseSurvey;
use App\Domains\Course\Import\BulkAdvancesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CourseController extends Controller
{

    public function getInternal()
    {
        return $this->serve(GetInternalCourseFeature::class);
    }

    public function getCourseFiltration()
    {
        return $this->serve(CourseFiltrationFeature::class);
    }

    public function getProPageDetails()
    {
        return $this->serve(GetProPageFeature::class);
    }

    public function show()
    {
        return $this->serve(GetCourseByIdFeature::class);
    }

    public function autocomplete()
    {

        return $this->serve(AutocompleteFiltrationFeature::class);
    }

    public function postEnroll()
    {
        return $this->serve(EnrollInCourseFeature::class);
    }

    public function postSurvey()
    {
        return $this->serve(SubmitCourseSurvey::class);
    }

    public function updateBulkAdvances(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $path = $request->file->move(public_path('/sheets'), $fileName);
        Excel::import(new BulkAdvancesImport(), $path);
        unlink($path);
    }
}
