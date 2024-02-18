<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Models\ChapterKnowledgeAssessmentTags;
use App\Domains\Course\Models\Competency;
use App\Domains\Course\Models\KSA;
use App\Domains\Course\Models\SpecialtyArea;
use App\Domains\Course\Rules\CoursePermission;
use ColumnTypes;
use Constants;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Payments\Models\PackageChapter;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;

class ChapterController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(Chapter $model)
    {
        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("course::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name'         => trans("course::lang.status"),
                'key'          => 'activation',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text'  => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    0 => [
                        'text'  => 'Deactivated',
                        'class' => 'badge badge-danger',
                    ],

                ]
            ],
            [
                'name' => trans("Sort"),
                'key'  => 'sort',
                'type' => ColumnTypes::STRING,
            ],
        ];
        $this->searchColumn = ["name"];
        $this->orderBy = null;
        parent::__construct();

        $this->permitted_actions = [
            'index'  => CoursePermission::COURSE_INDEX,
            'create' => CoursePermission::COURSE_CREATE,
            'edit'   => CoursePermission::COURSE_EDIT,
            'show'   => null,
            'delete' => CoursePermission::COURSE_DELETE,
        ];

        view()->share([
            'competencies_list'     => Competency::pluck('name', 'id'),
            'speciality_areas_list' => SpecialtyArea::pluck('name', 'id'),
            'ksa_list'              => KSA::pluck('name', 'id'),
        ]);

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
            "name"             => ["required", "string", "max:100"],
//            "drip_time"        => ["integer", "max:500", "min:0"],
            'activation'       => ['required'],
            "course_objective" => 'nullable|max:5000',
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"             => ["required", "string", "max:100"],
//            "drip_time"        => ["integer", "max:500", "min:0"],
            'activation'       => ['required'],
            "course_objective" => 'nullable|max:5000',
        ]);
    }

    public function isStored($row)
    {
        $package_chapter = PackageChapter::where('course_id', $row->course_id)->first();
        if ($package_chapter) {
            PackageChapter::create([
                'chapter_id' => $row->id,
                'package_subscription_id' => $package_chapter->package_subscription_id,
                'course_id'  => $row->course_id,
            ]);
        }

        foreach ($this->request->competency_id ?? [] as $key => $competency_id) {
            ChapterKnowledgeAssessmentTags::create([
                "course_id"          => $row->course_id,
                "chapter_id"         => $row->id,
                'competency_id'      => $this->request->competency_id[$key],
                'speciality_area_id' => $this->request->speciality_area_id[$key],
                'ksa_id'             => $this->request->ksa_id[$key],

            ]);
        }
    }


    public function isUpdated($row)
    {
        ChapterKnowledgeAssessmentTags::where('chapter_id', $row->id)->delete();
        foreach ($this->request->competency_id ?? [] as $key => $competency_id) {
            ChapterKnowledgeAssessmentTags::create([
                "course_id"          => $row->course_id,
                "chapter_id"         => $row->id,
                'competency_id'      => $this->request->competency_id[$key],
                'speciality_area_id' => $this->request->speciality_area_id[$key],
                'ksa_id'             => $this->request->ksa_id[$key],

            ]);
        }
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

    public function checkRequest()
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
