<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Models\CourseAssessment;
use App\Domains\Course\Rules\CoursePermission;
use ColumnTypes;
use Constants;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\Lesson;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;

class CourseAssessmentController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(CourseAssessment $model)
    {
        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("Question"),
                'key'  => 'question',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Correct Answer"),
                'key'  => function (CourseAssessment $row) {
                    return $row->correct_answer->answer ?? 'Has No Correct Answers';
                },
                'type' => ColumnTypes::CALLBACK,
            ],
            [
                'name'  => 'Related Lesson',
                'type'  => ColumnTypes::CALLBACK,
                'key'   => function (CourseAssessment $row) {
                    if($row->relatedLesson)
                        return "<a target='_blank' href='/admin/lesson/{$row->relatedLesson->id}/edit?course_id={$row->relatedLesson->course_id}&chapter_id={$row->relatedLesson->chapter_id}'>{$row->relatedLesson->name}</a>";
                    return 'Has No Related Lesson';
                },
            ]

        ];
        $this->searchColumn = ["question"];
        $this->orderBy = null;
        parent::__construct();

        $this->permitted_actions = [
            'index'  => CoursePermission::COURSE_INDEX,
            'create' => CoursePermission::COURSE_CREATE,
            'edit'   => CoursePermission::COURSE_EDIT,
            'show'   => null,
            'delete' => CoursePermission::COURSE_DELETE,
        ];

    }

    /**
     * @param $query
     */
    public function callbackQuery($query)
    {
        if ($this->request->course_id) {
            return $query->where('course_id', $this->request->course_id);
        }
        return abort(404);
    }


    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "question"  => ["required", "string", "max:255"],
            "answers.*" => ["required", "string"],
            "answers"   => ["array"],
            'course_id' => ['required']
        ]);
    }

    public function onCreate()
    {
        $lessons = Lesson::where('course_id', $this->request->course_id)->pluck('name', 'id')->toArray();
        view()->share(compact('lessons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response|Voild
     */
    public function store()
    {
        $this->ifMethodExistCallIt('onStore');


        $question = $this->model->create($this->request->only(['course_id', 'question', 'related_lesson_id']));

        $answers = [];
        foreach ($this->request->answers as $answer) {
            $item = [
                'answer'     => $answer,
                'is_correct' => 0
            ];
            array_push($answers, $item);
        }
        $is_correct = $this->request->is_correct;
        $answers[$is_correct]['is_correct'] = 1;
        $correct_answer_id = null;

        foreach ($answers as $answer) {
            $created_answer = $question->answers()->create($answer);
            if ($answer['is_correct'] == 1) {
                $correct_answer_id = $created_answer->id;
            }
        }

        $question->update([
            'correct_answer_id' => $correct_answer_id
        ]);


        $this->ifMethodExistCallIt('isStored', $question);
        return $this->returnMessage($question, 1);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name" => ["required", "string", "max:255"],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|Voild
     */
    public function destroy($id)
    {
        $id = $id ? $id : $this->request->id;
        $row = $this->model->find($id);
        $this->ifMethodExistCallIt('onDestroy', $row);
        $delete = $row->delete();
        $this->ifMethodExistCallIt('isDestroyed', $row);
        return back()->with('success', $this->successMessage(3, null));
    }

    private function checkRequest()
    {
        if (empty($this->request->course_id)) return abort(404);

        $course = Course::find($this->request->course_id);
        if ($course->course_type == CourseType::COURSE) {
            $this->pushBreadcrumb('Courses', url(Constants::ADMIN_BASE_URL . '/course'), true);
        } elseif ($course->course_type == CourseType::MICRODEGREE) {
            $this->pushBreadcrumb('Microdegrees', url(Constants::ADMIN_BASE_URL . '/micro-degree-course'), true);
        }

        $this->pushBreadcrumb($course->name, null, true);
        $this->pushBreadcrumb($this->module_name, url("{$this->route}?course_id={$this->request->course_id}"));

    }

    /**
     * @override
     */
    protected function setCustomProperty()
    {
        parent::setCustomProperty();
        $this->breadcrumb = []; // empty breadcrumb
        $this->checkRequest();  // rebuild breadcrumb


    }

}
