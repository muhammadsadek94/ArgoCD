<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Enum\VideoType;
use App\Domains\Course\Jobs\Common\UploadVideoToBrightCoveJob;
use App\Domains\Course\Models\LessonVoucher;
use App\Domains\Course\Rules\CoursePermission;
use ColumnTypes;
use Constants;
use DB;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Enum\CourseType;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Uploads\Jobs\UploadFileJob;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Domains\Course\Jobs\Common\UploadVideoToVimeoJob;
use App\Domains\Course\Jobs\Common\DeleteVideoFromBrightCoveJob;
use Vimeo\Vimeo;

class LessonController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(Lesson $model)
    {
        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("course::lang.name"),
                'key' => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("lang.type"),
                'key' => 'type',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    LessonType::QUIZ => [
                        'text' => trans('Quiz'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::VIDEO => [
                        'text' => trans('Video'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::DOCUMENT => [
                        'text' => trans('Rich Text'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::LAB => [
                        'text' => trans('LOD Labs'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::CYPER_Q => [
                        'text' => trans('CyberQ Labs'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::VOUCHER => [
                        'text' => trans('Exam Voucher'),
                        'class' => 'badge badge-success',
                    ],

                    LessonType::PROJECT => [
                        'text' => trans('Project'),
                        'class' => 'badge badge-success',
                    ],
                    LessonType::VITAL_SOURCE => [
                        'text' => 'Book',
                        'class' => 'badge badge-success',
                    ],
                    LessonType::CHECKPOINT => [
                        'text' => 'Checkpoint',
                        'class' => 'badge badge-success',
                    ],

                ]
            ],
            [
                'name' => trans("lang.activation"),
                'key' => 'activation',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text' => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    2 => [
                        'text' => trans('lang.pending'),
                        'class' => 'badge badge-warning',
                    ],

                    0 => [
                        'text' => 'Deactivated',
                        'class' => 'badge badge-danger',
                    ],

                ]
            ],
            [
                'name' => trans("Sort"),
                'key' => 'sort',
                'type' => ColumnTypes::STRING,
            ],

        ];
        $this->searchColumn = ["name"];
        $this->orderBy = null;
        parent::__construct();

        $this->permitted_actions = [
            'index' => CoursePermission::COURSE_INDEX,
            'create' => CoursePermission::COURSE_CREATE,
            'edit' => CoursePermission::COURSE_EDIT,
            'show' => null,
            'delete' => CoursePermission::COURSE_DELETE,
        ];
    }

    private function checkRequest()
    {
        if (empty($this->request->course_id) || empty($this->request->chapter_id)) return abort(404);

        $course = Course::find($this->request->course_id);
        $chapter = Chapter::find($this->request->chapter_id);

        if ($course->course_type == CourseType::COURSE) {
            $this->pushBreadcrumb('Courses', url(Constants::ADMIN_BASE_URL . '/course'), true);
        } elseif ($course->course_type == CourseType::MICRODEGREE) {
            $this->pushBreadcrumb('Microdegrees', url(Constants::ADMIN_BASE_URL . '/micro-degree-course'), true);
        }

        $this->pushBreadcrumb($course->name, null, true);
        $this->pushBreadcrumb('Chapter', url(Constants::ADMIN_BASE_URL . "/chapter?course_id={$this->request->course_id}"));
        $this->pushBreadcrumb($chapter->name, null, true);
        $this->pushBreadcrumb($this->module_name, url("{$this->route}?course_id={$this->request->course_id}&chapter_id={$this->request->chapter_id}"));
    }

    /**
     * @param $query
     */
    public function callbackQuery($query)
    {
        if ($this->request->course_id && $this->request->chapter_id) {
            return $query->where('course_id', $this->request->course_id)
                ->where('chapter_id', $this->request->chapter_id);
        }
        return abort(404);
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

    public function onStore()
    {
        parent::onStore();
        $this->validate($this->request, [
            "name" => ["required", "string", "max:100"],
            'activation' => ['required'],
            'type' => ['required'],
            'ilab_id' => ['required_if:type,' . LessonType::LAB]
        ], [
            'ilab_id.required_if' => ' the LOD Labs ID field is required when lesson type is Lab'
        ]);
        // check for only one voucher lesson

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {

        if (!$this->request->after_chapter) {
            $this->request->merge(['after_chapter' => $this->request->chapter_id]);
        }

        if ($this->request->type == LessonType::VOUCHER) {
            $lessons = Lesson::where('type', $this->request->type)
                ->where('course_id', $this->request->course_id)->count();
            if ($lessons > 0) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'There is already a voucher lesson exists',
                    'model' => null,
                    'redirect' => false
                ]);
            }
        }
        $this->ifMethodExistCallIt('onStore');

        $data = $this->request->all();

        if ($this->request->video_info && $this->request->type == LessonType::VIDEO) {
            $data['video'] = json_decode($this->request->video_info, true);
        }


        $insert = $this->model->create($data);
        $this->ifMethodExistCallIt('isStored', $insert);

        if ($this->request->ajax())
            return response()->json([
                'status' => 'true',
                'message' => $this->successMessage(1, null),
                'model' => $insert,
                'url' => url("{$this->route}/{$insert->id}/edit?course_id={$insert->course_id}&chapter_id={$insert->chapter_id}"),
                'redirect' => true
            ]);

        return redirect("{$this->route}/{$insert->id}/edit?course_id={$insert->course_id}&chapter_id={$insert->chapter_id}")->with('success', $this->successMessage(1, null));
    }

    public function edit($id)
    {
        $this->ifMethodExistCallIt('onEdit');
        $row = $this->model->findOrFail($id);
        $main_column = $this->main_column;
        $caption_data = VimeoController::getCaptionData();
        $vouchers = LessonVoucher::where('lesson_id', $id)->where('activation', 1)->get();
        $lessons = Lesson::where('course_id', $this->request->course_id)->pluck('name', 'id')->toArray();
        $this->pushBreadcrumb($row->$main_column, null, false);
        $this->pushBreadcrumb(trans('lang.edit'));

        return $this->view('edit', [
            'row' => $row,
            'caption_data' => $caption_data,
            'breadcrumb' => $this->breadcrumb,
            'vouchers' => $vouchers,
            'lessons' => $lessons
        ]);
    }

    public function onCreate()
    {
        $chapters = Chapter::where('course_id', $this->request->course_id)->pluck('name', 'id')->toArray();

        $course_type = Course::find($this->request->course_id)->course_type;

        view()->share(compact('chapters', 'course_type'));
    }

    public function onEdit()
    {
        $chapters = Chapter::where('course_id', $this->request->course_id)->pluck('name', 'id')->toArray();

        $course_type = Course::find($this->request->course_id)->course_type;

        view()->share(compact('chapters', 'course_type'));
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name" => ["required", "string", "max:100"],
            'activation' => ['required'],
            "manual" => ["mimes:xlsx, xls , doc ,docx,ppt,pptx,txt,pdf"],
            "image" => ["image"],

        ]);

        if ($this->request->has('manual') && !empty($this->request->manual)) {
            $this->attach($this->request->manual, 'manual_id');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response|Voild
     */
    public function update($id)
    {

        if (!$this->request->after_chapter) {
            $this->request->merge(['after_chapter' => $this->request->chapter_id]);
        }

        $this->ifMethodExistCallIt('onUpdate');
        $row = $this->model->find($id);
        if ($this->request->video_info && $row->type == LessonType::VIDEO) {
            $this->request->request->add(['video' => json_decode($this->request->video_info, true)]);
        }
        $update = $row->update($this->request->all());
        $this->ifMethodExistCallIt('isUpdated', $row);
        return $this->returnMessage($update, 2);
    }




    public function isUpdated($row)
    {
    }

    public function saveVideo($id)
    {
        $this->validate($this->request, [
            'video.file' => ['required', 'mimes:mp4']
        ]);

        $row = $this->model->find($id);
        $row->update([
            'video' => $this->request->video
        ]);
        dispatch_now(new UploadVideoToBrightCoveJob($row));

        return $this->returnMessage(true, 0);
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

    public function isDestroyed($row)
    {
        if ($row->type == LessonType::VIDEO) {
            dispatch_now(new DeleteVideoFromBrightCoveJob($row));
        }
    }

    private function attach($file, $name)
    {
        $file = dispatch_now(new UploadFileJob($file, 'lessons/resources'));
        $this->request->merge([$name => $file->id->toString()]);
    }
}
