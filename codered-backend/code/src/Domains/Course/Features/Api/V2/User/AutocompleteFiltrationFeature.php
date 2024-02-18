<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Payments\Repositories\LearnPathInfoRepository;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use DB;

class AutocompleteFiltrationFeature extends Feature
{

    public function handle(Request $request)
    {
        $keyword = preg_replace('/[^A-Za-z0-9\- ]/', '', $request->keyword);

        if ($request->has('keyword')) {
            $courses = DB::select("select `name`, `slug_url` as `id` from `courses` where `activation` = 1 and `course_type` = 1 and `name` like '%{$keyword}%' LIMIT 10");
            $learn_paths = DB::select("select `name`, `id`, `slug_url` from `learn_path_infos` where `type` in (1, 2, 3) and `learn_path_infos`.`activation` = 1 and `name` like '%{$keyword}%' LIMIT 10");
        }


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses' => $courses ?? [],
                'learn_paths' => $learn_paths ?? []
            ]
        ]);
    }
}
