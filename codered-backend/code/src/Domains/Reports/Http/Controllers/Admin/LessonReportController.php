<?php

namespace App\Domains\Reports\Http\Controllers\Admin;

use App\Domains\User\Models\User;
use App\Domains\Reports\Rules\LessonReportPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Enum\LessonType;
use ColumnTypes;
use Constants;
use DB;

class LessonReportController extends CoreController
{

    use HasAuthorization;

    public $domain = "reports";

     public function __construct(Lesson $model)
    {
         $this->model = $model;
         $this->select_columns = [
            [
                'name' => trans("reports::lang.lesson_name"),
                'type' => ColumnTypes::CALLBACK,
                'key'  => function ($row) {

                     return   "<a href='".url(Constants::ADMIN_BASE_URL . "/lesson/".$row->id. "/edit?course_id=".$row->course->id ." &chapter_id=".$row->chapter->id)."'>".$row->name."</a>";
                },
            ],
            [
                'name' => trans("reports::lang.course_name"),
                'type' => ColumnTypes::CALLBACK,
                'key'  => function ($row) {
                    return $row->course->name ?? null;
                },
            ],

            [
                'name'         => trans("lang.type"),
                'key'          => 'type',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    LessonType::QUIZ     => [
                        'text'  => trans('Quiz'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::VIDEO    => [
                        'text'  => trans('Video'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::DOCUMENT => [
                        'text'  => trans('Document'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::LAB      => [
                        'text'  => trans('Lab'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::CYPER_Q  => [
                        'text'  => trans('Lab'),
                        'class' => 'badge badge-success',
                    ],

                ]
            ],

            [
                'name' => trans("Sort"),
                'key'  => 'sort',
                'type' => ColumnTypes::STRING,
            ],

           

        ];
        $this->searchColumn = ["lessons.name","courses.name"];
        $this->orderBy = null;
        parent::__construct();

        $this->permitted_actions = [
            'index'  => LessonReportPermission::LESSON_REPORT_INDEX,
        ];


    }

   

    /**
     * @param $query
     */
    public function callbackQuery($query)
    {
      
        if ($this->request->video_id && $this->request->search) {
           
                return $query->join('courses', 'courses.id', '=', 'lessons.course_id')
                             ->select('lessons.*', DB::raw('courses.id AS course_id, courses.name AS 
                                       course_name')
                                     )
                             ->where(function ($query) {
                                     $query->whereRaw( 'JSON_EXTRACT(lessons.video,  "$.brightcove_ref_id") = CAST("null" AS JSON) OR JSON_EXTRACT(lessons.video, "$.video_id") = CAST("null" AS JSON) ' );        
                                })
                            ->where('lessons.activation',"=", 1)
                            ->where('courses.activation',"=", 1);

        }else if ($this->request->video_id){

                return $query->join('courses', 'courses.id', '=', 'lessons.course_id')
                             ->select('lessons.*', DB::raw('courses.id AS course_id, courses.name AS 
                                       course_name')
                                     )
                             ->where(function ($query) {
                                     $query->whereRaw( 'JSON_EXTRACT(lessons.video,  "$.brightcove_ref_id") = CAST("null" AS JSON) OR JSON_EXTRACT(lessons.video, "$.video_id") = CAST("null" AS JSON) ' );        
                                })
                            ->where('lessons.activation',"=", 1)
                            ->where('courses.activation',"=", 1);
             
        }else{
            
            return abort(404);
        }
    }
}
