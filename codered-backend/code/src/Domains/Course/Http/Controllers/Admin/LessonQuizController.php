<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Models\LessonMsq;
use App\Domains\Course\Rules\CoursePermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;

class LessonQuizController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(LessonMsq $model)
    {
        $this->model = $model;

        $this->permitted_actions = [
            'index'  => CoursePermission::COURSE_INDEX,
            'create' => CoursePermission::COURSE_CREATE,
            'edit'   => CoursePermission::COURSE_EDIT,
            'show'   => null,
            'delete' => CoursePermission::COURSE_DELETE,
        ];

        parent::__construct();
    }

    public function onStore()
    {
        parent::onStore();
        $this->validate($this->request, [
            "question"    => ["required", "string", "max:100"],
            "description" => ["required", "string"],
            "answers.*"   => ["required", "string"],
            "answers"     => ["array"],
            'lesson_id'   => ['required', 'exists:lessons,id']
        ], [
            'answers.*.required' => 'All answers fields are required'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response|Voild
     */
    public function store()
    {
        $this->ifMethodExistCallIt('onStore');

        $data = [
            'lesson_id'             => $this->request->lesson_id,
            'question'              => $this->request->question,
            'description'           => $this->request->description,
            'answers'               => [],
            'related_lesson_id'     => $this->request->related_lesson_id,
        ];

        $answers = [];
        foreach ($this->request->answers as $answer) {
            $item = [
                'text'       => $answer,
                'is_correct' => 0
            ];
            array_push($answers, $item);
        }

        $is_correct = $this->request->is_correct;
        $answers[$is_correct]['is_correct'] = 1;
        $data['answers'] = json_encode($answers);

        $insert = $this->model->create($data);
        $this->ifMethodExistCallIt('isStored', $insert);
        return $this->returnMessage($insert, 1);
    }


    //    public function onUpdate()
    //    {
    //        parent::onUpdate();
    //
    //        $this->validate($this->request, [
    //            "question"  => ["required", "string", "max:100"],
    //            "answer"    => ["required", "string"],
    //            'lesson_id' => ['required', 'exists:lessons,id']
    //        ]);
    //    }

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

}
