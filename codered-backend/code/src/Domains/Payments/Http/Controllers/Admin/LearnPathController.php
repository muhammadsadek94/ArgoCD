<?php

namespace App\Domains\Payments\Http\Controllers\Admin;

use App\Domains\Cms\Models\Brand;
use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Enterprise\Rules\WeightlRule;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Payments\Rules\LearnPathPermission;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Enum\ColumnTypes;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Payments\Enum\LearnPathType;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Payments\Enum\PayableType;
use App\Domains\Payments\Models\LearnPathCategory;
use App\Domains\Payments\Models\LearnPathCourse;
use App\Domains\Payments\Models\LearnPathPackage;
use App\Domains\Payments\Models\PaymentIntegration;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Domains\Payments\Models\PackageSubscription;
use Composer\Package\Package;

class LearnPathController extends CoreController
{
    use HasAuthorization;

    public $domain = "payments";

    public function __construct(LearnPathInfo $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.settings'), null, true);
        $this->pushBreadcrumb(trans('lang.lookups'), null, true);

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
                    CourseActivationStatus::ACTIVE           => [
                        'text'  => 'Published',
                        'class' => 'badge badge-success',
                    ],
                    CourseActivationStatus::DEACTIVATED      => [
                        'text'  => 'Unpublished',
                        'class' => 'badge badge-danger',
                    ],


                ]
            ],
            [
                'name'         => trans("Type"),
                'key'          => 'type',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    LearnPathType::CAREER         => [
                        'text'  => trans('Career path'),
                        'class' => 'badge badge-success',
                    ], LearnPathType::CERTIFICATE => [
                        'text' => trans('certificate path'),
                        'class' => 'badge badge-success',
                    ], LearnPathType::SKILL => [
                        'text' => trans('skill path'),
                        'class' => 'badge badge-success',
                    ], LearnPathType::BUNDLE_CATEGORY => [
                        'text' => trans('bundle category'),
                        'class' => 'badge badge-success',
                    ], LearnPathType::BUNDLE_COURSES => [
                        'text' => trans('bundle courses'),
                        'class' => 'badge badge-success',
                    ]

                ],

            ],

        ];
        $this->searchColumn = ["name"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => LearnPathPermission::LEARN_PATH_INDEX,
            'create' => LearnPathPermission::LEARN_PATH_CREATE,
            'edit'   => LearnPathPermission::LEARN_PATH_EDIT,
            // 'show'   => LearnPathPermission::Lesh,
            'delete' => LearnPathPermission::LEARN_PATH_DELETE,
        ];
    }

    public function onCreate()
    {
        parent::onCreate();

        $this->sharedViews();
    }

    public function onStore()
    {

        parent::onStore();
        $this->validate($this->request, [
            "name"              => ["required", "string", "max:100"],
            'slug_url'          => ['required', "unique:learn_path_infos,slug_url,{$this->request->micro_degree_course}", 'max:100'],
            "image_id"          => ['required', "exists:uploads,id"],
            "cover_id"          => ['required', "exists:uploads,id"],
            "category_id"       => ['required', "exists:course_categories,id"],
            //            "package_id"                => ['required', "exists:package_subscriptions,id"],
            'overview'          => ["required", "string", "max:500"],
            'for_who'           => ["required", "string", "max:255"],
            'learn'             => "required|array|min:1|max:10",
            'learn.*'           => ["required", "string", "max:100"],
            // 'description'       => ["required", "string", "max:200"],
            'avg_salary'        => ['required', 'int', 'min:1'],
            'price'             => ['required', 'string', 'min:1'],
            "payment_url"       => ["required", "url", "max:100"],
        //    'price_description' => ["required", "string", "max:100"],
            "level"                     => ['required'],

            'features'           => "required|array|min:1|max:10",
            'features.*'         => "required|string|max:100",
            'subtitles'          => "required|array|min:1|max:10",
            'subtitles.*'        => "required|string|max:100",
            'prerequisite'       => "required|array|min:1|max:10",
            'prerequisite.*'     => "required|string|max:100",
            // 'jobs'               => "required|array|min:1|max:10",
            // 'jobs.*'             => "required|string|max:100",
            'skills'             => "required|array|min:1|max:10",
            'skills.*'           => "required|string|max:100",
//            'skills_description' => ["required", "string", "max:100"],
//            'jobs_description' => ["required", "string", "max:100"],
            'faq' => "required|array|min:1|max:10",
            // 'metadata' => "required|array|min:1|max:10",
            // "metadata.*" => "required|string|max:255",

//            'skills_description' => ["required", "string", "max:100"],
//            'jobs_description'   => ["required", "string", "max:100"],

            "courses"   => "required|array|min:1",
            "courses.*" => "required|string|distinct|min:1",
            // "weights"   => ["required", "array", "min:1", new WeightlRule($this->request->type)],
            // "weight.*"  => "required|int|min:1",

            // "sorts"   => ["array", "min:1", "required_if:type,". LearnPathType::BUNDLE_COURSES.",".LearnPathType::CAREER.",".LearnPathType::SKILL.",".LearnPathType::CERTIFICATE],
            // "sorts.*" => "int|min:1|required_if:type,". LearnPathType::BUNDLE_COURSES.",".LearnPathType::CAREER.",".LearnPathType::SKILL.",".LearnPathType::CERTIFICATE,
        ]);
        $check_category = CourseCategory::find($this->request->category_id)->cat_parent_id;
        if(!empty($check_category)) { //category is child
            $this->request->request->add(['sub_category_id' => $this->request->category_id]);
            $this->request->merge(['category_id' => null]);
        }
        $faq = $this->formatFaqField($this->request->faq['question'], $this->request->faq['answer']);
        $metadata = $this->formatMetadataField($this->request->metadata['name'], $this->request->metadata['content']);
        $this->request->request->add(['faq' => $faq,'metadata'=>$metadata]);
    }

    public function isStored($row)
    {
        $this->addCourses($row);
        $row->jobRoles()->sync($this->request->job_role_id);
        $row->specialtyAreas()->sync($this->request->specialty_area_id);
        $row->tools()->sync($this->request->tools);
    }

    public function onEdit()
    {
        parent::onEdit();
        $this->sharedViews();
    }

    public function onUpdate()
    {
        parent::onUpdate();
        $this->validate($this->request, [
            "name"              => ["required", "string", "max:100"],
            'slug_url'    => ['required', "unique:learn_path_infos,slug_url,{$this->request->learn_path}", 'max:255'],
            "image_id"          => ['required', "exists:uploads,id"],
            "cover_id"          => ['required', "exists:uploads,id"],
            "category_id"       => ['required', "exists:course_categories,id"],
            //            "package_id"                => ['required', "exists:package_subscriptions,id"],
            'overview'          => ["required", "string", "max:500"],
            'for_who'           => ["required", "string", "max:255"],
            'learn'             => "required|array|min:1|max:10",
            'learn.*'           => ["required", "string", "max:100"],
            // 'description'       => ["required", "string", "max:200"],
            'avg_salary'        => ['required', 'int', 'min:1'],
            'price'             => ['required', 'string', 'min:1'],
            "payment_url"       => ["required", "url", "max:100"],
            //    'price_description' => ["required", "string", "max:100"],
            "level"                     => ['required'],

            'features'           => "required|array|min:1|max:10",
            'features.*'         => "required|string|max:100",
            'subtitles'          => "required|array|min:1|max:10",
            'subtitles.*'        => "required|string|max:100",
            'prerequisite'       => "required|array|min:1|max:10",
            'prerequisite.*'     => "required|string|max:100",
            // 'jobs'               => "required|array|min:1|max:10",
            // 'jobs.*'             => "required|string|max:100",
            'skills'             => "required|array|min:1|max:10",
            'skills.*'           => "required|string|max:100",
//            'skills_description' => ["required", "string", "max:100"],
//            'jobs_description' => ["required", "string", "max:100"],
            'faq' => "required|array|min:1|max:10",
            // 'metadata' => "required|array|min:1|max:10",
            // "metadata.*" => "required|string|max:255",

//            'skills_description' => ["required", "string", "max:100"],
//            'jobs_description'   => ["required", "string", "max:100"],

            "courses"   => "required|array|min:1",
            "courses.*" => "required|string|distinct|min:1",
            // "weights"   => ["required", "array", "min:1", new WeightlRule($this->request->type)],
            // "weight.*"  => "required|int|min:1",

            // "sorts"   => ["array", "min:1", "required_if:type,". LearnPathType::BUNDLE_COURSES.",".LearnPathType::CAREER.",".LearnPathType::SKILL.",".LearnPathType::CERTIFICATE],
            // "sorts.*" => "int|min:1|required_if:type,". LearnPathType::BUNDLE_COURSES.",".LearnPathType::CAREER.",".LearnPathType::SKILL.",".LearnPathType::CERTIFICATE,
        ]);

        $metadata = $this->formatMetadataField($this->request->metadata['name'], $this->request->metadata['content']);
        $this->request->merge(['metadata'=>($metadata)]);
        $check_category = CourseCategory::find($this->request->category_id)->cat_parent_id;
        if(!empty($check_category)) { //category is child
            $this->request->request->add(['sub_category_id' => $this->request->category_id]);
            $this->request->merge(['category_id' => null]);
        }

        $faq = $this->formatFaqField($this->request->faq['question'], $this->request->faq['answer']);
        $this->request->merge(['faq' => $faq]);
    }

    public function isUpdated($row)
    {
        $this->updateCourses($row);
        $row->jobRoles()->sync($this->request->job_role_id);
        $row->specialtyAreas()->sync($this->request->specialty_area_id);
        $row->tools()->sync($this->request->tools);
    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

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

    public function formatMetadataField($names, $contents): \Illuminate\Support\Collection
    {
        return collect($names)
            ->combine($contents)
            ->map(function ($value, $key) {

                return [
                    'name' => $key,
                    'content'=> $value
                ];
            })->values();
    }

    public function addCourses(LearnPathInfo $row)
    {
        if ($row->type != LearnPathType::BUNDLE_CATEGORY) {
            $access_ids = $this->request->courses;
        } else {
            $access_ids = $this->request->categories;
        }

        if ($row->type != LearnPathType::BUNDLE_CATEGORY) {

            foreach ($access_ids as $index => $course_id) {
                LearnPathCourse::updateOrCreate(
                    ['learn_path_id' => $row->id->toString(), 'course_id' => $course_id],
                    [
                        // 'weight' => $this->request->weights[$index],
                        'weight' => 100/ count($access_ids) ,
                        'course_id' => $course_id,
                        'learn_path_id' => $row->id->toString(),
                        'sort' => $index + 1
                        // 'sort' => $this->request->sorts[$index]
                    ]
                );
            }
            LearnPathCourse::whereNotIn('course_id', $access_ids)->where('learn_path_id', $row->id->toString())->delete();
        }
        else{
            foreach ($access_ids as $index => $course_category_id) {
                LearnPathCategory::updateOrCreate(
                    ['learn_path_id' => $row->id->toString(), 'course_category_id' => $course_category_id],
                    [
                        'course_category_id' => $course_category_id,
                        'learn_path_id' => $row->id->toString(),
                    ]
                );
            }
            LearnPathCategory::whereNotIn('course_category_id', $access_ids)->where('learn_path_id', $row->id->toString())->delete();
        }

        $row->save();
    }

    public function updateCourses($row)
    {

        if ($row->type != LearnPathType::BUNDLE_CATEGORY) {
            $access_ids = $this->request->courses;

            foreach ($access_ids as $index => $course_id) {
                $data[$course_id] = [
                    'weight' => is_array($this->request->weights) ? $this->request->weights[$index] : 100 / count($access_ids) ,
                    'course_id' => $course_id,
                    'learn_path_id' => $row->id,
                    'sort' => is_array($this->request->sorts) ? $this->request->sorts[$index] : $index
                ];
                LearnPathCourse::updateOrCreate(
                    ['learn_path_id' => $row->id, 'course_id' => $course_id],
                    [
                        'weight' => 100 / count($access_ids) ,
                        'course_id' => $course_id,
                        'learn_path_id' => $row->id,
                        'sort' => $index + 1
                    ]
                );
            }
            LearnPathCourse::whereNotIn('course_id', $access_ids)->where('learn_path_id', $row->id)->delete();
            //        $package->courses()->sync($data);
        }
        else{
            $access_ids = $this->request->categories;

            foreach ($access_ids as $index => $course_category_id) {
                LearnPathCategory::updateOrCreate(
                    ['learn_path_id' => $row->id, 'course_category_id' => $course_category_id],
                    [
                        'course_category_id' => $course_category_id,
                        'learn_path_id' => $row->id,
                    ]
                );
            }
            LearnPathCategory::whereNotIn('course_category_id', $access_ids)->where('learn_path_id', $row->id)->delete();
        }
    }

    private function sharedViews()
    {
        $micro_degrees_list = Course::Microdegrees()->get()->pluck('internal_name_or_name', 'id');
        $packages_list = PackageSubscription::pluck('name', 'id');
        $categories_list = CourseCategory::active()
            ->parent()
            ->get()
            ->pluck('name', 'id');

        $sub_categories_list = CourseCategory::active()
            ->child()
            ->pluck('name', 'id');


        $level_list = CourseLevelRepository::getPluckLevels();
        $courses_list = Course::get()->pluck('internal_name_or_name', 'id');
        $tools = Brand::pluck('alt_text', 'id');
        return view()->share(compact('categories_list', 'sub_categories_list', 'courses_list', 'level_list' , 'micro_degrees_list', 'packages_list', 'tools'));
    }


    public function postPackage()
    {
        $this->validate($this->request, [
            'name'       => 'required',
            'amount'     => 'required',
            'features'   => 'required|array',
            'features.*' => 'required',
            'path_id'  => 'required'
        ]);

        LearnPathPackage::create($this->request->all());

        return $this->returnMessage(true, 0);

    }

    public function deletePackage($package_id)
    {
        LearnPathPackage::destroy($package_id);

        return back()->with('success', $this->successMessage(3, null));

    }
}
