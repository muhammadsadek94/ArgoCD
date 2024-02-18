<?php

namespace App\Domains\Payments\Http\Controllers\Admin;

use App\Domains\Course\Models\Chapter;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Payments\Rules\PackageSubscriptionPermission;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Payments\Models\PackageChapter;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Mpdf\Tag\Dd;
use phpDocumentor\Reflection\Types\Null_;

class PackageSubscriptionController extends CoreController
{
    use HasAuthorization;

    public $domain = "payments";

    public function __construct(PackageSubscription $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.pages'), null, true);

        $this->select_columns = [
            [
                'name' => trans("course::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Amount"),
                'key'  => 'amount',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Type / Duration"),
                'key'  => function ($row) {
                    if ($row->type == SubscriptionPackageType::MONTHLY) {
                        return '
                        <span style="" class="badge badge-success">
							Monthly
						</span>
                        ';
                    }
                    if ($row->type == SubscriptionPackageType::ANNUAL) {
                        return '
                        <span style="" class="badge badge-success">
							Yearly
						</span>
                        ';
                    }

                    if ($row->type == SubscriptionPackageType::CUSTOM) {
                        return '
                        <span style="" class="badge badge-success">
							' . $row->duration . '
						</span>
                        ';
                    }
                },
                'type' => ColumnTypes::CALLBACK,
            ],
            [
                'name'         => trans("Package Type"),
                'key'          => 'access_type',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    AccessType::COURSES         => [
                        'text'  => trans('(Bundle) Courses Access'),
                        'class' => 'badge badge-success',
                    ],
                    AccessType::COURSE_CATEGORY => [
                        'text'  => trans('(Bundle) Categories Access'),
                        'class' => 'badge badge-success',
                    ],
                    AccessType::PRO             => [
                        'text'  => trans('Pro Access'),
                        'class' => 'badge badge-success',
                    ],

                    AccessType::LEARN_PATH_CAREER => [
                        'text'  => trans('Learn Path Career'),
                        'class' => 'badge badge-success',
                    ],

                    AccessType::LEARN_PATH_CERTIFICATE => [
                        'text'  => trans('Learn Path Certificate'),
                        'class' => 'badge badge-success',
                    ],

                    AccessType::LEARN_PATH_SKILL  => [
                        'text'  => trans('Learn Path Skill'),
                        'class' => 'badge badge-success',
                    ],
                    AccessType::INDIVIDUAL_COURSE => [
                        'text'  => trans('(Individual) Course'),
                        'class' => 'badge badge-success',
                    ]

                ]
            ],

            [
                'name'         => trans("lang.activation"),
                'key'          => 'activation',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text'  => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    0 => [
                        'text'  => trans('lang.suspended'),
                        'class' => 'badge badge-danger',
                    ],

                ]
            ],

        ];
        $this->searchColumn = ["name"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_INDEX,
            'create' => PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_CREATE,
            'edit'   => PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_EDIT,
            'show'   => null,
        ];

        $this->shareDataToViews();
    }

    public function callbackQuery($query)
    {
        return $query->where('enterprise_id', null);

        //            ->whereIn('access_type', [AccessType::LEARN_PATH_SKILL, AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE])
        //        where('type', '!=', SubscriptionPackageType::Enterprise)
    }

    public function onCreate()
    {
        parent::onCreate();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            'name'              => ['required', 'string', 'max:50'],
            'amount'            => 'required',
            'sku'               => ['required', 'string', 'max:100', 'unique:package_subscriptions,sku'],
            'type'              => ['required'],
            'learn_path_id'     => ['required_if:access_type,5,7,6'],
            'activation'        => ['required', 'boolean'],
            'access_permission' => ['required'],
            'free_trial_days'   => ['min:0', 'nullable', 'numeric']

        ]);

        if (in_array($this->request->access_type, [AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_SKILL, AccessType::INDIVIDUAL_COURSE])) {
            if ($this->request->free_trial_days != Null) {
                $days = $this->request->free_trial_days ? $this->request->free_trial_days : 1;
                $this->validate($this->request, [
                    'duration' => ['required', 'numeric', "min:$days", 'max:10000'],
                ]);
            } else {

                $this->validate($this->request, [
                    'duration' => ['required', 'numeric', 'min:1', 'max:10000'],
                ]);
            }
        }

        if ($this->request->access_type == AccessType::LEARN_PATH_SKILL || $this->request->access_type == AccessType::LEARN_PATH_CAREER || $this->request->access_type == AccessType::LEARN_PATH_CERTIFICATE) {
            $this->request->merge(['type' => 3]);
        }

        if (empty($this->request->learn_path_id)) {
            $this->request->request->remove('learn_path_id');
        }
    }

    public function store()
    {
        $this->ifMethodExistCallIt('onStore');

        if ($this->request->access_type == AccessType::INDIVIDUAL_COURSE && $this->request->individual_courses) {
            $course = Course::find($this->request->individual_courses[0]);
            $this->request->merge(['course_type' => $course?->course_type]);
        }

        if ($this->validateChapters())
            return $this->validateChapters();

        $insert = $this->model->create($this->request->all());
        $this->ifMethodExistCallIt('isStored', $insert);
        return $this->returnMessage($insert, 1);
    }

    public function isStored($row)
    {
        if ($this->request->access_type == AccessType::COURSES) {
            $access_ids = $this->request->courses;
            $row->update(['access_id' => json_encode($access_ids)]);
        }

        if ($this->request->access_type == AccessType::INDIVIDUAL_COURSE) {
            $access_ids = $this->request->individual_courses;
            $row->update(['access_id' => json_encode($access_ids)]);
            if ($this->request->chapters) {
                foreach ($this->request->chapters as $chapter_id => $data) {
                    PackageChapter::create([
                        'package_subscription_id'  => $row->id->toString(),
                        'chapter_id'               => $chapter_id,
                        'course_id'                => $data['course_id'],
                        'after_installment_number' => $data['installment'] ?? 0,
                        'is_free_trial'            => $data['is_free_trial'] ?? 0,
                    ]);
                }
            }
        }

        if ($this->request->access_type == AccessType::COURSE_CATEGORY) {
            $access_ids = $this->request->categories;
            $row->update(['access_id' => json_encode($access_ids)]);
        }
        if (
            $this->request->access_type == AccessType::LEARN_PATH_CERTIFICATE ||
            $this->request->access_type == AccessType::LEARN_PATH_SKILL ||
            $this->request->access_type == AccessType::LEARN_PATH_CAREER
        ) {
            $access_ids = $this->request->courses_list;
            $row->update(['access_id' => json_encode($access_ids)]);

            $row->refresh();

            foreach ($access_ids as $i => $course_id) {
                CourseWeight::create([
                    'package_subscription_id' => $row->id,
                    'course_id'               => $course_id,
                    'sort'                    => $this->request->sorts[$i],
                    'weight'                  => $this->request->weights[$i]
                ]);
            }
        }
    }

    public function onEdit()
    {
        parent::onEdit();
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            'name'          => ['required', 'string', 'max:50'],
            'amount'        => 'required',
            'sku'           => ['required', 'string', 'max:100'],
            'learn_path_id' => 'required_if:access_type,5,7,6',
            'type'          => 'required',
            'activation'    => ['required', 'boolean'],
            'free_trial_days'   => ['min:0', 'nullable', 'numeric']
        ]);

        if (in_array($this->request->access_type, [AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_SKILL, AccessType::INDIVIDUAL_COURSE])) {
            if ($this->request->free_trial_days != Null) {
                $days = $this->request->free_trial_days ? $this->request->free_trial_days : 1;
                $this->validate($this->request, [
                    'duration' => ['required', 'numeric', "min:$days", 'max:10000'],
                ]);
            } else {

                $this->validate($this->request, [
                    'duration' => ['required', 'numeric', 'min:1', 'max:10000'],
                ]);
            }
        }
    }

    public function update($id)
    {
        if ($this->request->access_type == AccessType::LEARN_PATH_SKILL || $this->request->access_type == AccessType::LEARN_PATH_CAREER || $this->request->access_type == AccessType::LEARN_PATH_CERTIFICATE) {
            $this->request->merge(['type' => 3]);
        }

        if ($this->request->access_type == AccessType::INDIVIDUAL_COURSE && $this->request->individual_courses) {
            $course = Course::find($this->request->individual_courses[0]);
            $this->request->merge(['course_type' => $course?->course_type]);
        }

        $data = $this->request->all();
        $data['learn_path_id'] = $this->request->learn_path_id == "" ? null : $this->request->learn_path_id;
        $this->ifMethodExistCallIt('onUpdate');

        if ($this->validateChapters())
            return $this->validateChapters();

        $row = $this->model->find($id);
        $update = $row->update($data);
        $this->ifMethodExistCallIt('isUpdated', $row);
        return $this->returnMessage($update, 2);
    }

    public function isUpdated($row)
    {

        if ($this->request->access_type == AccessType::COURSES) {
            $access_ids = $this->request->courses;
            $row->update(['access_id' => json_encode($access_ids)]);
        }

        if ($this->request->access_type == AccessType::INDIVIDUAL_COURSE) {
            $access_ids = $this->request->individual_courses;
            $row->update(['access_id' => json_encode($access_ids)]);

            if ($this->request->chapters) {
                $package_chapters = array_values($row->chapters()->pluck('chapter_id')->toArray());
                $request_chapters = array_keys($this->request->chapters);
                $same_values = (count($package_chapters) == count($request_chapters) && !array_diff($package_chapters, $request_chapters));

                if (!$same_values)
                    $row->package_chapters()->delete();

                foreach ($this->request->chapters as $chapter_id => $data) {
                    if ($same_values) {
                        PackageChapter::where('package_subscription_id', $row->id)->where('chapter_id', $chapter_id)->update([
                            'after_installment_number' => $data['installment'] ?? 0,
                            'is_free_trial'            => $data['is_free_trial'] ?? 0,
                        ]);
                    } else {
                        PackageChapter::create([
                            'package_subscription_id'  => $row->id,
                            'chapter_id'               => $chapter_id,
                            'course_id'                => $data['course_id'],
                            'after_installment_number' => $data['installment'] ?? 0,
                            'is_free_trial'            => $data['is_free_trial'] ?? 0,
                        ]);
                    }
                }
            }
        }

        if ($this->request->access_type == AccessType::COURSE_CATEGORY) {
            $access_ids = $this->request->categories;
            $row->update(['access_id' => json_encode($access_ids)]);
        }

        if (
            $this->request->access_type == AccessType::LEARN_PATH_CERTIFICATE ||
            $this->request->access_type == AccessType::LEARN_PATH_SKILL ||
            $this->request->access_type == AccessType::LEARN_PATH_CAREER
        ) {
            $access_ids = $this->request->courses_list;
            $row->update(['access_id' => json_encode($access_ids)]);
            $row->refresh();
            foreach ($access_ids as $index => $course_id) {
                $data[$course_id] = [
                    'weight'                  => $this->request->weights[$index],
                    'course_id'               => $course_id,
                    'package_subscription_id' => $row->id,
                    'sort'                    => $this->request->sorts[$index]
                ];
                CourseWeight::updateOrCreate(
                    ['package_subscription_id' => $row->id, 'course_id' => $course_id],
                    [
                        'weight'                  => $this->request->weights[$index],
                        'course_id'               => $course_id,
                        'package_subscription_id' => $row->id,
                        'sort'                    => $this->request->sorts[$index]
                    ]
                );
            }
            CourseWeight::whereNotIn('course_id', $access_ids)->where('package_subscription_id', $row->id)->delete();
        }
    }

    private function shareDataToViews()
    {
        $categories_list = CourseCategory::active()
            ->get()
            ->map(function ($category) {

                if (!empty($category->cat_parent_id && isset($category->parent_category))) {
                    $name = "{$category->parent_category->name} -> {$category->name}";
                } else {
                    $name = "{$category->name}";
                }

                return [
                    'id'   => $category->id,
                    'name' => $name
                ];
            })->pluck('name', 'id');
        $courses_list = Course::get()->pluck('internal_name_or_name', 'id');
        $learn_paths = LearnPathInfo::pluck('name', 'id');

        $chapters = Course::first()->chapters;

        return view()->share(compact('categories_list', 'courses_list', 'learn_paths', 'chapters'));
    }

    public function duplicate()
    {
        $package_subscription = $this->model->find($this->request->package_subscription);

        $package_subscription->load('courses');
        $newSubscription = $package_subscription->replicate();
        $newSubscription->name = 'Copy - ' . $package_subscription->name;
        $newSubscription->created_at = Carbon::now();
        $newSubscription->push();

        if (
            $newSubscription->access_type == AccessType::LEARN_PATH_CERTIFICATE ||
            $newSubscription->access_type == AccessType::LEARN_PATH_SKILL ||
            $newSubscription->access_type == AccessType::LEARN_PATH_CAREER
        ) {
            foreach ($newSubscription->courses as $course) {
                CourseWeight::create([
                    'package_subscription_id' => $newSubscription->id,
                    'course_id'               => $course->id,
                    'sort'                    => $course->pivot->sort,
                    'weight'                  => $course->pivot->weight
                ]);
            }
        }

        return redirect("$this->route")->with('success', $this->successMessage(true, 1));
    }

    public function validateChapters()
    {
        if ($this->request->access_type == AccessType::INDIVIDUAL_COURSE && ($this->request->free_trial_days > 0 || $this->request->is_installment)) {
            $not_has_free_trial = true;
            $exceeded_installment_count = false;
            $has_free_trial_and_installment = false;
            $has_negative_number = false;
            $has_installment_and_not_free_trailer = false;
            foreach ($this->request->chapters as $chapter_id => $data) {
                if (array_key_exists('is_free_trial', $data) && $this->request->free_trial_days > 0 && $data['is_free_trial'] == 1) {
                    $not_has_free_trial = false;
                }

                if (array_key_exists('is_free_trial', $data) && $data['is_free_trial'] == 1 && array_key_exists('installment', $data) && $data['installment'] > 0) {
                    $has_free_trial_and_installment = true;
                }

                if (array_key_exists('is_free_trial', $data) && $data['is_free_trial'] == 0 && array_key_exists('installment', $data) && $data['installment'] <= 0) {
                    $has_installment_and_not_free_trailer = true;
                }

                if (array_key_exists('installment', $data) && $data['installment'] > $this->request->installment_count) {
                    $exceeded_installment_count = true;
                }

                if (array_key_exists('installment', $data) && $data['installment'] && $data['installment'] < 0) {
                    $has_negative_number = true;
                }
            }
            if ($this->request->free_trial_days && $not_has_free_trial) {
                return response()->json([
                    'status'  => 'false',
                    'message' => 'You must select at least one chapter as free trial',
                    'error'   => false
                ]);
            }

            if ($has_free_trial_and_installment) {
                return response()->json([
                    'status'  => 'false',
                    'message' => 'If chapter has free trial, installment number must be 0 or empty',
                    'error'   => false
                ]);
            }

            if ($has_installment_and_not_free_trailer) {
                return response()->json([
                    'status'  => 'false',
                    'message' => 'If chapter is not free trial, installment number must be greater than  0',
                    'error'   => false
                ]);
            }
            if ($exceeded_installment_count) {
                return response()->json([
                    'status'  => 'false',
                    'message' => 'You must select installment number less than or equal to installments count',
                    'error'   => false
                ]);
            }

            if ($has_negative_number) {
                return response()->json([
                    'status'  => 'false',
                    'message' => 'Installment number must be greater than or equal to 0',
                    'error'   => false
                ]);
            }
        }

        return false;
    }

    public function fetchCourseChapters()
    {

        $chapters = Chapter::where('course_id', $this->request->course_id)->get();

        return response()->json(['data' => $chapters]);
    }
}
