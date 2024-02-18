<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Cms\Models\Brand;
use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CoursePackage;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Rules\CoursePermission;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\User\Models\User;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Rules\SlugRule;
use App\Foundation\Traits\HasAuthorization;
use Carbon\Carbon;
use DB;

class CourseController extends CoreController
{
    use HasAuthorization;

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
                'name' => trans("Course Category"),
                'type' => ColumnTypes::CALLBACK,
                'key'  => function ($row) {
                    return $row->sub ? $row->sub->name : $row->category->name ?? null;
                },
            ],
            [
                'name'         => trans("course::lang.status"),
                'key'          => 'activation',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    CourseActivationStatus::ACTIVE           => [
                        'text'  => 'Published',
                        'class' => 'badge badge-success',
                    ],
                    CourseActivationStatus::DEACTIVATED      => [
                        'text'  => 'Unpublished',
                        'class' => 'badge badge-danger',
                    ],
                    CourseActivationStatus::DRAFT            => [
                        'text'  => 'Draft',
                        'class' => 'badge badge-info',
                    ],
                    CourseActivationStatus::PENDING_APPROVAL => [
                        'text'  => 'Pending Approval',
                        'class' => 'badge badge-warning',
                    ],
                    CourseActivationStatus::HIDDEN => [
                        'text'  => 'Hidden Course',
                        'class' => 'badge badge-warning',
                    ],


                ]
            ]
        ];
        $this->searchColumn = ["name"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => CoursePermission::COURSE_INDEX,
            'create' => CoursePermission::COURSE_CREATE,
            'edit'   => CoursePermission::COURSE_EDIT,
            'delete' => CoursePermission::COURSE_DELETE,
        ];
    }

    public function callbackQuery($query)
    {

        if($this->request->has('activation')){
            $query->where('activation', $this->request->activation);
        }

        if($this->request->has('type') && in_array(1, $this->request->type) && !in_array(2, $this->request->type)){
            $query->where('is_editorial_pick', 1);
        }

        if($this->request->has('type') && in_array(2, $this->request->type) && !in_array(1, $this->request->type)){
            $query->where('is_best_seller', 1);
        }

        if($this->request->has('type') && in_array(3, $this->request->type) && !in_array(1, $this->request->type)
            && !in_array(2, $this->request->type)){
            $query->where('is_featured', 1);
        }

        if($this->request->has('type') && in_array(2, $this->request->type) && in_array(1, $this->request->type)
            && in_array(2, $this->request->type)){
            $query->where('is_best_seller', 1)->where('is_editorial_pick', 1)->where('is_featured', 1);
        }

        if($this->request->has('course_sub_category_id') && !empty($this->request->course_sub_category_id)){
            $query->where('course_sub_category_id', $this->request->course_sub_category_id);
        }
        return $query->Course();
    }

    public function onIndex()
    {
        $sub_categories_list = CourseCategory::active()->whereNotNull('cat_parent_id')
            ->pluck('name', 'id');

        view()->share(compact('sub_categories_list'));
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
            "name"                      => ["required", "string", "max:100"],
            "image_id"                  => ['required', "exists:uploads,id"],
            "cover_id"                  => ['required', "exists:uploads,id"],
            "intro_video"               => ['required', "url"],
            "sku"                      => ["required", "string", "max:100"],
            "course_category_id"        => ["required", "exists:course_categories,id"],
            "course_sub_category_id"        => ["required", "exists:course_categories,id"],
            "course_tags_id.*"          => ["exists:course_tags,id"],
            "job_role_id.*"             => ["exists:job_roles,id"],
            "specialty_area_id.*"       => ["exists:specialty_areas,id"],
            "user_id"                   => ["required", "exists:users,id"],
            "level"                     => ['required'],
            'brief'                     => ['required'],
            'description'               => ['required'],
//            'cyberq_course_id'          => ['required', 'max:255'],
            'internal_name'             => ['required', 'max:255'],
            'activation'                => ['required'],
           /* 'learn'                     => 'required|array',*/
            'subtitles'                 => 'required|array',
            'prerequisites'             => 'required|array',
            'slug_url'                  => ['required', 'unique:courses', 'max:255', new SlugRule],
            'commission_percentage'     => ['integer', 'max:100', 'min:0'],
            'price'                     => ['numeric', 'max:9999', 'min:0'],
            'discount_price'            => ['numeric', 'max:9999', 'min:0'],
            'advances'                  => [ 'numeric', 'max:9999', 'min:0'],
            // 'metadata'                  => "required|array|min:1|max:10",
            // 'metadata.*.*'              => "required|string|distinct|max:255",

        ]);

//        $check_category = CourseCategory::find($this->request->course_category_id)->cat_parent_id;
//        if(!empty($check_category)) { //category is child
//            $this->request->request->add(['course_sub_category_id' => $this->request->course_category_id]);
//            $this->request->merge(['course_category_id' => null]);
//        }
        $metadata = $this->formatMetadataField($this->request->metadata['name'], $this->request->metadata['content']);
        $this->request->merge(['metadata'=>($metadata)]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response|Voild
     */
    public function store()
    {
        $this->ifMethodExistCallIt('onStore');
        $insert = $this->model->create($this->request->all());
        $this->ifMethodExistCallIt('isStored', $insert);
        return $this->returnMessage($insert, 1);

    }

    public function isStored($row)
    {
        $row->tags()->sync($this->request->course_tags_id);
        $row->jobRoles()->sync($this->request->job_role_id);
        $row->specialtyAreas()->sync($this->request->specialty_area_id);
        $row->instructors()->sync($this->request->user_id);
    //        $row->tools()->sync($this->request->tools);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"                      => ["required", "string", "max:100"],
            "image_id"                  => ['required', "exists:uploads,id"],
            "cover_id"                  => ['required', "exists:uploads,id"],
            "intro_video"               => ['required', "url"],
            "sku"                      => ["required", "string", "max:100"],
            "course_category_id"        => ["required", "exists:course_categories,id"],
            "course_sub_category_id"        => ["required", "exists:course_categories,id"],

            "course_tags_id.*"          => ["exists:course_tags,id"],
            "job_role_id.*"             => ["exists:job_roles,id"],
            "specialty_area_id.*"       => ["exists:specialty_areas,id"],
            "user_id"                   => ["required", "exists:users,id"],
            "level"                     => ['required'],
            'internal_name'             => ['required', 'max:255'],
            'brief'                     => ['required'],
            'description'               => ['required'],
            'activation'                => ['required'],
//            'cyberq_course_id'          => ['required', 'max:255'],
            'learn'                     => 'required|array',
            'subtitles'                 => 'required|array',
            'prerequisites'             => 'required|array',
            'slug_url'                  => ['required', "unique:courses,slug_url,{$this->request->course}", 'max:255', new SlugRule],
            'commission_percentage'     => ['integer', 'max:100', 'min:0'],
            'price'                     => ['required', 'numeric', 'max:9999', 'min:0'],
            'discount_price'            => [ 'numeric', 'max:9999', 'min:0'],
            'advances'                  => [ 'numeric', 'max:9999', 'min:0'],
            // 'metadata'                  => 'required|array|min:1|max:10',
            // 'metadata.*.*'              => "required|string|max:255",
        ]);

//        $check_category = CourseCategory::find($this->request->course_category_id)->cat_parent_id;
//        if(!empty($check_category)) { //category is child
//            $this->request->request->add(['course_sub_category_id' => $this->request->course_category_id]);
//            $this->request->merge(['course_category_id' => null]);
//        }
        $metadata = $this->formatMetadataField($this->request->metadata['name'], $this->request->metadata['content']);
        $this->request->merge(['metadata'=>($metadata)]);
    }

    public function isUpdated($row)
    {
        $row->tags()->sync($this->request->course_tags_id);
        $row->jobRoles()->sync($this->request->job_role_id);
        $row->specialtyAreas()->sync($this->request->specialty_area_id);
        $row->instructors()->sync($this->request->user_id);
        if($row->is_free){
        $this->deleteCoursePackages($row->id);
        }

        //        $row->tools()->sync($this->request->tools);
    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }


    public function patchUpdateTiming(Course $course)
    {
        $this->hasPermission(CoursePermission::COURSE_EDIT);

        $this->validate($this->request, [
            "timing" => ["required", "integer"],
        ]);

        $course->update(request(['timing']));

        return response()->json(['status' => 'true', 'message' => $this->successMessage(2, null)]);
    }


    private function sharedFormData()
    {
        $categories_list = CourseCategory::active()
            ->parent()
            ->get()
             ->map(function($category, $key) {

                 if(!empty($category->cat_parent_id && isset($category->parent_category))) {
                     $name = "{$category->parent_category->name} -> {$category->name}";
                 } else {
                     $name = "{$category->name}";
                 }

                 return [
                     'id' => $category->id,
                     'name' => $name
                 ];
             })
            ->pluck('name', 'id');

            $sub_categories_list = CourseCategory::active()
            ->child()
            ->pluck('name', 'id');



        $activation_list = CourseActivationStatus::getActivationList();
        $level_list = CourseLevelRepository::getPluckLevels();
        $course_type_list = CourseType::getList();
//        $tools = Brand::pluck('alt_text', 'id');
        return view()->share(compact('categories_list', 'sub_categories_list', 'activation_list', 'level_list', 'course_type_list'));
    }

    public function getTrainers()
    {
        $users = User::active()
            ->Provider()
            ->where(function ($query) {
                $query->where("first_name", "like", "%{$this->request->search}%");
            })
            ->get()->map(function ($row) {
                return [
                    'id'   => $row->id,
                    'text' => $row->full_name,
                ];
            });

        return $users;

    }


    public function postPackage()
    {
        $this->validate($this->request, [
            'name'       => 'required',
            'amount'     => 'required',
            'features'   => 'required|array',
            'features.*' => 'required',
            'course_id'  => 'required'
        ]);

        CoursePackage::create($this->request->all());

        return $this->returnMessage(true, 0);

    }

    public function deleteCoursePackages($id){

        CoursePackage::where('course_id',$id )->delete();
    }

    public function deletePackage($package_id)
    {
        CoursePackage::destroy($package_id);

        return response()->json(['status' => 'true', 'message' => $this->successMessage(2, null)]);

    }


    public function formatMetadataField($names, $contents): \Illuminate\Support\Collection
    {
        return collect($names)
            ->combine($contents)
            ->map(function ($value, $key) {
                return [
                    'name' => $key,
                    'content'   => $value
                ];
            })->values();
    }

    public function duplicate()
    {
        $course = $this->model->find($this->request->course_id);

        $course->load('chapters', 'assessments', 'chapters.lessons', 'image', 'packages', 'tags', 'jobRoles', 'specialtyAreas');

        $newCourse = $course->replicate();
        $newCourse->name = 'Copy - ' . $course->name;
        $newCourse->created_at = Carbon::now();
        $newCourse->push();

        foreach($course->chapters as $chapter) {
            $newChapter = $chapter->replicate();
            $newChapter->course_id = $newCourse->id;
            $newChapter->created_at = Carbon::now();
            $newChapter->push();

            foreach($chapter->lessons()->where('type', '!=', LessonType::QUIZ)->get() as $lesson) {

                $newLesson = $lesson->replicate();
                $newLesson->chapter_id = $newChapter->id;
                $newLesson->course_id = $newCourse->id;
                $newLesson->created_at = Carbon::now();
                $newLesson->push();

                foreach($lesson->resources as $resource) {
                    $newResource = $resource->replicate();
                    $newResource->lesson_id = $newLesson->id;
                    $newResource->created_at = Carbon::now();
                    $newResource->push();
                }

                foreach($lesson->mcq as $mcq) {
                    $newMcq = $mcq->replicate();
                    $newMcq->lesson_id = $newLesson->id;
                    $newMcq->related_lesson_id = null;
                    $newMcq->created_at = Carbon::now();
                    $newMcq->push();
                }

                foreach($lesson->faq as $faq) {
                    $newFaq = $faq->replicate();
                    $newFaq->lesson_id = $newLesson->id;
                    $newFaq->created_at = Carbon::now();
                    $newFaq->push();
                }

                foreach($lesson->lesson_objectives as $objective) {
                    $newObjective = $objective->replicate();
                    $newObjective->lesson_id = $newLesson->id;
                    $newObjective->chapter_id = $newChapter->id;
                    $newObjective->course_id = $newCourse->id;
                    $newObjective->created_at = Carbon::now();
                    $newObjective->push();
                }

                foreach($lesson->lesson_tasks as $task) {
                    $newTask = $task->replicate();
                    $newTask->lesson_id = $newLesson->id;
                    $newTask->chapter_id = $newChapter->id;
                    $newTask->course_id = $newCourse->id;
                    $newTask->created_at = Carbon::now();
                    $newTask->push();
                }

            }

            foreach($chapter->lessons()->where('type', LessonType::QUIZ)->get() as $lesson) {

                $newLesson = $lesson->replicate();
                $newLesson->chapter_id = $newChapter->id;
                $newLesson->course_id = $newCourse->id;
                $newLesson->created_at = Carbon::now();
                $newLesson->push();

                foreach($lesson->resources as $resource) {
                    $newResource = $resource->replicate();
                    $newResource->lesson_id = $newLesson->id;
                    $newResource->created_at = Carbon::now();
                    $newResource->push();
                }

                foreach($lesson->mcq as $mcq) {
                    $old_related_lesson_sort = $mcq->relatedLesson->sort;
                    $new_related_lesson = $newChapter->lessons()->where('sort', $old_related_lesson_sort)->first();
                    $newMcq = $mcq->replicate();
                    $newMcq->lesson_id = $newLesson->id;
                    $newMcq->related_lesson_id = $new_related_lesson ? $new_related_lesson->id : null;
                    $newMcq->created_at = Carbon::now();
                    $newMcq->push();
                }

                foreach($lesson->faq as $faq) {
                    $newFaq = $faq->replicate();
                    $newFaq->lesson_id = $newLesson->id;
                    $newFaq->created_at = Carbon::now();
                    $newFaq->push();
                }

                foreach($lesson->lesson_objectives as $objective) {
                    $newObjective = $objective->replicate();
                    $newObjective->lesson_id = $newLesson->id;
                    $newObjective->chapter_id = $newChapter->id;
                    $newObjective->course_id = $newCourse->id;
                    $newObjective->created_at = Carbon::now();
                    $newObjective->push();
                }

                foreach($lesson->lesson_tasks as $task) {
                    $newTask = $task->replicate();
                    $newTask->lesson_id = $newLesson->id;
                    $newTask->chapter_id = $newChapter->id;
                    $newTask->course_id = $newCourse->id;
                    $newTask->created_at = Carbon::now();
                    $newTask->push();
                }

            }
        }

        foreach($course->tags as $tag) {
            DB::table('course_course_tag')->insert([
                'course_id' => $newCourse->id,
                'course_tag_id' => $tag->id
            ]);
        }

        foreach($course->jobRoles as $role) {
            DB::table('job_rollables')->insert([
                'job_rollable_id' => $newCourse->id,
                'job_role_id' => $role->id,
                'job_rollable_type' => Course::class
            ]);
        }

        foreach($course->specialtyAreas as $area) {
            DB::table('specialty_areables')->insert([
                'specialty_areable_id' => $newCourse->id,
                'specialty_area_id' => $area->id,
                'specialty_areable_type' => Course::class
            ]);
        }

        foreach($course->assessments as $assessment) {
            $newAssessment = $assessment->replicate();
            $newAssessment->course_id = $newCourse->id;
            $newAssessment->created_at = Carbon::now();
            $newAssessment->related_lesson_id = null;
            $newAssessment->push();

            if($assessment->correct_answer) {
                $newCorrectAnswer = $assessment->correct_answer->replicate();
                $newCorrectAnswer->course_assessments_id = $newAssessment->id;
                $newCorrectAnswer->push();
            }

            foreach($assessment->answers as $anser) {
                $newAnswer = $anser->replicate();
                $newAnswer->course_assessments_id = $newAssessment->id;
                $newAnswer->created_at = Carbon::now();
                $newAnswer->push();
            }

        }

        foreach($course->packages as $package) {
            $newPackage = $package->replicate();
            $newPackage->course_id = $newCourse->id;
            $newPackage->created_at = Carbon::now();
            $newPackage->push();
        }

        return back()->with('success', "Course Duplicated Successfully with name {$newCourse->name}");
    }

}
