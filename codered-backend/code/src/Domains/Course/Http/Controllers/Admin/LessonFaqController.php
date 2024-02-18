<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Models\LessonFaq;
use App\Domains\Course\Rules\CoursePermission;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;

class LessonFaqController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(LessonFaq $model)
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
            "question"  => ["required", "string", "max:100"],
            "answer"    => ["required", "string"],
            'lesson_id' => ['required', 'exists:lessons,id']
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "question"  => ["required", "string", "max:100"],
            "answer"    => ["required", "string"],
            'lesson_id' => ['required', 'exists:lessons,id']
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

}
