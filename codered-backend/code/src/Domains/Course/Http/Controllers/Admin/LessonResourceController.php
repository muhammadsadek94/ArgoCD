<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Enum\LessonResourceTypes;
use App\Domains\Course\Rules\CoursePermission;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Models\LessonResource;
use App\Foundation\Http\Controllers\Admin\CoreController;

class LessonResourceController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(LessonResource $model)
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
            "name"          => ["required", "string"],
            "link"          => ["required_if:type,".LessonResourceTypes::LINK, "url"],
            "attachment_id" => ["required_if:type,".LessonResourceTypes::FILE, "mimes:pdf,epub,pptx"],
            'lesson_id'     => ['required', 'exists:lessons,id']
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
