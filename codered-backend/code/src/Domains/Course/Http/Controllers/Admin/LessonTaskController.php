<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\LessonObjective;
use App\Domains\Course\Models\LessonTask;
use App\Domains\Course\Rules\CoursePermission;
use App\Domains\Course\Rules\LessonTaskPermission;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Enum\Constants;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class LessonTaskController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(LessonTask $model)
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
            "title"       => ["required", "string", 'max:255'],
            "description"       => ["required", "string"],
            'sort' => ['required', 'integer']
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
