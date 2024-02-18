<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Models\CourseWhatToLearn;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CoursePackage;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Rules\CoursePermission;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\User\Models\User;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Rules\SlugRule;
use App\Foundation\Traits\DuplicateFeature;
use App\Foundation\Traits\HasAuthorization;
use Carbon\Carbon;
use DB;

class MicroDegreeCourseController extends CoreController
{
    use HasAuthorization, DuplicateFeature;

    public $domain = "course";

    public function __construct(Course $model)
    {
        $this->model = $model;

        $this->select_columns = [
            [
                'name' => trans("course::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name'         => trans("lang.activation"),
                'key'          => 'activation',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    CourseActivationStatus::ACTIVE           => [
                        'text'  => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    CourseActivationStatus::DEACTIVATED      => [
                        'text'  => 'Deactivated',
                        'class' => 'badge badge-danger',
                    ],
                    CourseActivationStatus::DRAFT            => [
                        'text'  => 'Draft',
                        'class' => 'badge badge-info',
                    ],
                    CourseActivationStatus::PENDING_APPROVAL => [
                        'text'  => 'Waiting For approve',
                        'class' => 'badge badge-warning',
                    ],

                    CourseActivationStatus::HIDDEN => [
                        'text'  => 'hidden',
                        'class' => 'badge badge-warning',
                    ],

                ]
            ]
        ];
        $this->searchColumn = ["name"];
        parent::__construct();
        if (is_array($this->breadcrumb) && count($this->breadcrumb) > 0){
            $this->breadcrumb[0]->title = "MicroDegrees";
        }

        $this->permitted_actions = [
            'index'  => CoursePermission::COURSE_INDEX,
            'create' => CoursePermission::COURSE_CREATE,
            'edit'   => CoursePermission::COURSE_EDIT,
            'delete' => CoursePermission::COURSE_DELETE,
        ];
    }

    public function callbackQuery($query)
    {
        return $query->Microdegrees();
    }

    public function onEdit()
    {
        parent::onEdit();

        $this->sharedFormData();
    }

    public function onCreate()
    {
        parent::onCreate();

        $this->sharedFormData();
    }

    public function onStore()
    {
        parent::onStore();


        $this->validate($this->request, [
            "name"              => ["required", "string", "max:100"],
            "image_id"          => ['required', "exists:uploads,id"],
            "cover_id"          => ['required', "exists:uploads,id"],
            "intro_video"       => ['required', "url"],
            "syllabus_url"      => ['url'],
            "user_id.*"         => ["required", "exists:users,id"],
//            'cyberq_course_id'  => ['required', 'max:255'],
            'internal_name'     => ['required', 'max:255'],
            "user_id"           => ["required"],
            'brief'             => ['required'],
            'prerequisites'     => ['required'],
            'average_salary'    => ['required'],
            'estimated_time'    => ['required'],
            'activation'        => ['required'],
            'slug_url'          => ['required', 'unique:courses', 'max:255', new SlugRule],
            'price'             => ['required', 'numeric', 'max:9999', 'min:0']


        ]);


        //$learn = $this->formatLearnField($this->request->learn['title'], $this->request->learn['description']);
        //$this->request->request->add(['learn' => $learn]);

        $faq = $this->formatFaqField($this->request->faq['question'], $this->request->faq['answer']);
        $this->request->request->add(['faq' => $faq]);
        $project = $this->formatProjectField($this->request->project['title'], $this->request->project['description']);
        $this->request->request->add(['project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response|Voild
     */
    public function store()
    {
        $this->ifMethodExistCallIt('onStore');
        $insert = $this->model->create($this->request->except('user_id'));
        $this->ifMethodExistCallIt('isStored', $insert);
        if ($this->request->ajax())
            return response()->json([
                'status'   => 'true',
                'message'  => $this->successMessage(1, null),
                'model'    => $insert,
                'url'      => url("{$this->route}/{$insert->id}/edit"),
                'redirect' => true
            ]);

        return redirect("{$this->route}/{$insert->id}/edit")->with('success', $this->successMessage(1, null));
    }

    public function isStored(Course $row)
    {
        $row->instructors()->sync($this->request->user_id);
        $row->microdegree()->create($this->request->only([
            'prerequisites', 'average_salary', 'estimated_time', 'faq', 'syllabus_url', 'slack_url', 'key_features', 'skills_covered', 'project'
        ]));
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"              => ["required", "string", "max:100"],
            "image_id"          => ['required', "exists:uploads,id"],
            "cover_id"          => ['required', "exists:uploads,id"],
            "intro_video"       => ['required', "url"],
            "syllabus_url"      => ["url"],
            "user_id.*"         => ["required", "exists:users,id"],
            "user_id"           => ["required"],
            'internal_name'     => ['required', 'max:255'],
//            'cyberq_course_id'  => ['required', 'max:255'],
            'brief'             => ['required'],
            'prerequisites'     => ['required'],
            'average_salary'    => ['required'],
            'estimated_time'    => ['required'],
            'activation'        => ['required'],
            'slug_url'          => ['required', "unique:courses,slug_url,{$this->request->micro_degree_course}", 'max:255', new SlugRule],
            'price'             => ['required', 'numeric', 'max:9999', 'min:0'],
            'slack_url'         => 'url'
        ]);

        /* $learn = $this->formatLearnField($this->request->learn['title'], $this->request->learn['description']);
        $this->request->request->add(['learn' => $learn]);*/

        $faq = $this->formatFaqField($this->request->faq['question'], $this->request->faq['answer']);
        $this->request->request->add(['faq' => $faq]);
        $project = $this->formatProjectField($this->request->project['title'], $this->request->project['description']);
        $this->request->request->add(['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response|Voild
     */
    public function update($id)
    {
        $this->ifMethodExistCallIt('onUpdate');
        $row = $this->model->find($id);
        if(empty($this->request->menu_cover_id)){
            $update = $row->update($this->request->except('user_id', 'menu_cover_id'));
        } else {
            $update = $row->update($this->request->except('user_id'));
        }
        $this->ifMethodExistCallIt('isUpdated', $row);
        return $this->returnMessage($update, 2);
    }

    public function isUpdated($row)
    {
        $row->instructors()->sync($this->request->user_id);

        $row->microdegree()->update($this->request->only([
            'prerequisites', 'average_salary', 'estimated_time', 'faq', 'syllabus_url', 'slack_url', 'key_features', 'skills_covered', 'project'
        ]));
    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

    public function postPdf()
    {
        $this->validate($this->request, [
            'file' => 'required|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

    private function sharedFormData()
    {
        $categories_list = CourseCategory::active()
            ->get()
            ->map(function ($category) {

                if (!empty($category->cat_parent_id && isset( $category->parent_category))) {
                    $name = "{$category->parent_category->name} -> {$category->name}";
                } else {
                    $name = "{$category->name}";
                }

                return [
                    'id' => $category->id,
                    'name' => $name
                ];
            })->pluck('name', 'id');
        $activation_list = CourseActivationStatus::getActivationList();
        $level_list = CourseLevelRepository::getPluckLevels();
        return view()->share(compact('categories_list', 'activation_list', 'level_list'));
    }

    public function getTrainers()
    {
        $users = User::active()
            ->Provider()
            ->where(function ($query) {
                $query->whereRaw("concat(first_name, ' ', last_name) like ?","%{$this->request->search}%")
                    ->orWhere('email', 'LIKE', "%{$this->request->search}%");
            })
            ->get()->map(function ($row) {
                return [
                    'id'   => $row->id,
                    'text' => $row->full_name,
                ];
            });

        return $users;
    }

    /**
     * @param $titles
     * @param $descriptions
     * @return \Illuminate\Support\Collection
     */
    public function formatLearnField($titles, $descriptions): \Illuminate\Support\Collection
    {
        return collect($titles)
            ->combine($descriptions)
            ->map(function ($value, $key) {
                return [
                    'title'       => $key,
                    'description' => $value
                ];
            })->values();
    }

    /**
     * @param $questions
     * @param $answers
     * @return \Illuminate\Support\Collection
     */
    public function formatFaqField($questions, $answers): \Illuminate\Support\Collection
    {
        return collect($questions)
            ->combine($answers)
            ->map(function ($value, $key) {
                return [
                    'question' => $key,
                    'answer'   => $value
                ];
            })->values();
    }


    public function formatProjectField($questions, $answers): \Illuminate\Support\Collection
    {
        return collect($questions)
            ->combine($answers)
            ->map(function ($value, $key) {
                return [
                    'title' => $key,
                    'description'   => $value
                ];
            })->values();
    }

    /**
     * Package management
     */

    public function postPackage()
    {
        $this->validate($this->request, [
            'name'       => 'required',
            'amount'     => 'required',
            'type'       => 'required',
            'features'   => 'required|array',
            'features.*' => 'required',
            'course_id'  => 'required'
        ]);

        CoursePackage::create($this->request->all());

        return $this->returnMessage(true, 0);
    }

    public function deletePackage($package_id)
    {
        CoursePackage::destroy($package_id);

        return back()->with('success', $this->successMessage(3, null));
    }

    public function postWhatToLearn()
    {

        $this->validate($this->request, [
            'title'             => 'required | max:18',
            'description'       => 'required',
            'what_image_id'     => 'required',
            'course_id'         => 'required'
        ]);

        $this->request['image_id'] = $this->request->what_image_id;

        CourseWhatToLearn::create($this->request->all());

        return $this->returnMessage(true, 0);
    }

    public function deleteWhatToLearn($course_id)
    {

        CourseWhatToLearn::destroy($course_id);

        return back()->with('success', $this->successMessage(3, null));
    }
}
