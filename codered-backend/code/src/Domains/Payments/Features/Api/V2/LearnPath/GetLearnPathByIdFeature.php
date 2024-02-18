<?php

namespace App\Domains\Payments\Features\Api\V2\LearnPath;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathFullInfoResource;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;

class GetLearnPathByIdFeature extends Feature
{

    public function handle(Request $request)
    {

        $user = $request->user('api');

        $learn_path = LearnPathInfo::where(function ($query) use ($request) {
            $query->where('id', $request->id)
                ->orWhere('slug_url', $request->id);
        })
            ->with([
                'allCourses' => function ($query) {
                    $query->orderBy('sort', 'asc')
                        ->with([
                            'chapters' => function ($query) {
                                $query->active()->select('id', 'course_id', 'name', 'description', 'drip_time', 'agg_lessons')
                                ->with('lessons', function ($query) {
                                    return $query->with('chapter', 'course')->active();
                                });
                            }]);
                }])
            ->with('cover:id,path,full_url,mime_type', 'image:id,path,full_url,mime_type')
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedCourses' => function ($query) use ($user) {
                        $query->where('user_id', $user?->id);
                    }]);
                $query->with([
                    'allPathCertificates' => function ($query) use ($user) {
                        $query->select('id', 'learnpath_id', 'user_id')->where('user_id', $user->id);
                    }]);

            })
            ->first();

        if (!$learn_path) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'message' => 'Learn Path not exists!',
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'learn_path' => new LearnPathFullInfoResource($learn_path),
                'packages'   => $learn_path->pathPackages()->limit(8)->get()
            ]
        ]);
    }
}
