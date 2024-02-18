<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Models\CourseReview;
use App\Domains\Course\Rules\CoursePermission;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use Str;

class ReviewsController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(CourseReview $model)
    {
        $this->model = $model;

        $this->select_columns = [
            [
                'name' => 'Course Name',
                'key' => function ($row) {
                    return $row->course->name ?? null;
                },
                'type' => ColumnTypes::CALLBACK,
            ],
            [
                'name' => 'Review Rate',
                'type' => ColumnTypes::STRING,
                'key' => 'rate'
            ],
            [
                'name' => 'Review',
                'type' => ColumnTypes::CALLBACK,
                'key' => function ($row) {
                    return Str::limit($row->user_goals, 70);
                },
            ],
            [
                'name' => trans("Status"),
                'key' => 'activation',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text' => trans('Published'),
                        'class' => 'badge badge-success',
                    ],
                    0 => [
                        'text' => trans('lang.suspended'),
                        'class' => 'badge badge-danger',
                    ],
                ]
            ],
        ];
        $this->searchColumn = ["name"];

        parent::__construct();
        $this->permitted_actions = [
            'index' => CoursePermission::COURSE_INDEX,
            'delete' => CoursePermission::COURSE_DELETE,
            'show' => null
        ];
        $this->isShowable = true;
    }


    public function callbackQuery($query)
    {

        // dd($this->request->rate);

        if ($this->request->has('rate') || $this->request->rate != null) {
            if ($this->request->rate == 0)
                $query->whereBetween('rate', [0, 1]);

            elseif ($this->request->rate == 1)
                $query->whereBetween('rate', [1, 2]);

            elseif ($this->request->rate == 2)
                $query->whereBetween('rate', [2, 3]);

            elseif ($this->request->rate == 3)
                $query->whereBetween('rate', [3, 4]);

            elseif ($this->request->rate == 4)
                $query->whereBetween('rate', [4, 5]);
        }
        return $query;
    }

    public function changeStatus($review, $status)
    {

        CourseReview::find($review)->update(['activation' => $status]);
        return redirect()->back();
    }
}
