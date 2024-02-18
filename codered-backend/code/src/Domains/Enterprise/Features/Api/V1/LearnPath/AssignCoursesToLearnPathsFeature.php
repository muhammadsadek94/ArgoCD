<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Http\Requests\Api\V1\LearnPath\AssignCourseToLearnPathRequest;
use App\Domains\Enterprise\Jobs\Api\V1\LearnPath\AsyncLearnPathCourseWithPackageJob;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class AssignCoursesToLearnPathsFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(AssignCourseToLearnPathRequest $request)
    {
        $admin = auth()->user();


        $package_subscription = PackageSubscription::active()->where('id',$request->package_subscription_id)->where(function ($query) use ($admin) {
            $query->whereNull('enterprise_id')
                ->orWhere('enterprise_id', $admin->id);
        })->first();
        if (!$package_subscription) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'learning path',
                    "message" => 'there is no learning paths found'
                ]
            ]);
        }

        $package_subscription->access_id = json_encode( $request->course);
        $package_subscription->save();
        // add multiple rows in course weight
        $data = [];

        if ($request->has('weight')) {
//
            foreach ($request->course as $index => $course_id) {
                $data   [$course_id] = ['weight' => $request->weight[$index], 'course_id' => $course_id, 'package_subscription_id' => $request->package_subscription_id, 'sort' => $index + 1];
            }
        } else {
            $weight = 100 / count($request->course);
//
            foreach ($request->course as $index => $course_id) {
                $data   [$course_id] = ['weight' => $weight, 'course_id' => $course_id, 'package_subscription_id' => $request->package_subscription_id, 'sort' => $index + 1];
            }
        }
        foreach ($data as  $row){
            CourseWeight::updateOrCreate(
                ['package_subscription_id' => $package_subscription->id, 'course_id' => $row['course_id']],
                [
                    'weight' => $row['weight'],
                    'course_id' => $row['course_id'],
                    'package_subscription_id' => $row['package_subscription_id'],
                    'sort' => $row['sort']
                ]
            );
        }
        CourseWeight::whereNotIn('course_id', $request->course)->where('package_subscription_id', $package_subscription->id)->delete();

//        $package_subscription->courses()->sync($data);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'message' => 'success'
            ]
        ]);
    }
}
