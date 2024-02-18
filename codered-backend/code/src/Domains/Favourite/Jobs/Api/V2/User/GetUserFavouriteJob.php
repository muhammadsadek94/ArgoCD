<?php

namespace App\Domains\Favourite\Jobs\Api\V2\User;

use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Enum\LessonActivationStatus;
use App\Domains\Property\Models\Property;
use App\Domains\Favourite\Enum\FavouriteType;
use App\Domains\Favourite\Models\Favourite;
use INTCore\OneARTFoundation\Job;

class GetUserFavouriteJob extends Job
{
    private $user_id;
    private $type;
    private $perPage;

    /**
     * Create a new job instance.
     *
     * @param string $user_id
     * @param string $type
     * @param int    $perPage
     */
    public function __construct(string $user_id, string $type, $perPage = 10)
    {
        $this->user_id = $user_id;
        $this->type = $type;
        $this->perPage = $perPage;
    }

    /**
     * Execute the job.
     *
     * @return Favourite
     */
    public function handle()
    {
        $morphs = FavouriteType::getMorphs();
        $model = new $morphs[$this->type];

        $favourable_ids = Favourite::where([
            'favourable_type' => $this->type,
            'user_id'         => $this->user_id
        ])->pluck('favourable_id')->toArray();

        return $model->whereIn('id', $favourable_ids)
            ->when($this->type == FavouriteType::LESSON, function ($query) {
                return $query->where('activation', LessonActivationStatus::ACTIVE)
                    ->whereHas('course', function ($query) {
                        $query->where('activation', '!=', CourseActivationStatus::DEACTIVATED);
                    })
                    ->with([
                        'course' => function ($query) {
                            $query->with([
                                'completedPercentageLoad' => function ($query) {
                                    $query->where('user_id', $this->user_id);
                                }])
                                ->with([
                                    'lessons' => function ($query) {
                                        $query->select('id', 'chapter_id', 'name', 'course_id', 'time', 'type', 'is_free', 'activation')->active()->whereHas('chapter', function ($query) {
                                            $query->active();
                                        });
                                    }])
                                ->with(['image', 'category', 'cover', 'sub', 'reviews', 'category.image', 'sub.image'])
                                ->withCount([
                                    'reviews' => function ($query) {
                                        $query->where('activation', 1);
                                    }])
                                ->withCount('cousre_enrollments as course_enrollments_count');
                        }]);
            })
            ->get();
    }
}
