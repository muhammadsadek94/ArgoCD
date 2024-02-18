<?php

namespace App\Domains\Course\Features\Api\V2;

use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\Menu\MenuResource;
use App\Domains\Course\Http\Resources\Api\V2\Menu\TagsBasicInfoResource;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Models\Lookups\CourseTag;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use DB;
use Framework\Traits\SelectColumnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use INTCore\OneARTFoundation\Feature;

class MenuFeature extends Feature
{

    use SelectColumnTrait;

    const CACHE_TTL = 86400;

    public function __construct()
    {
    }

    public function handle(Request $request)
    {

        $menu = Cache::remember('website_menu_v2', self::CACHE_TTL, function () {
            return CourseCategory::select(SelectColumnTrait::$categoryColumns)
                ->active()
                ->parent()
                ->with([
                    'sub_categories' => function ($query) {
                        $query->select(SelectColumnTrait::$categoryColumns)
                            ->with([
                                'coursesAssignWithSub' => function ($query) {
                                    $query->course()
                                        ->active()
                                        ->select(SelectColumnTrait::$coursesColumns);
                                },
                                'pathsAssignWithSub'   => function ($query) {
                                    $query->paths()
                                        ->select(SelectColumnTrait::$learnPathBasicColumns)
                                        ->active()
                                        ->with(SelectColumnTrait::$learnPathsCoursesColumnsInline);
                                }
                            ]);
                    }
                ])
                ->get()
                ->each(function ($category) {
                    $category->sub_categories->each(function ($subCategory) {
                        $subCategory->coursesAssignWithSub = $subCategory->coursesAssignWithSub->take(7);
                    });
                });
        });

        $tags = Cache::remember('website_tags', self::CACHE_TTL, function () {
            return CourseTag::select('id', 'name', 'activation', 'is_featured')->active()->feature()->limit(30)->get();
        });

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'menu' => MenuResource::collection($menu),
                'tags' => TagsBasicInfoResource::collection($tags)
            ],
        ]);

    }
}
