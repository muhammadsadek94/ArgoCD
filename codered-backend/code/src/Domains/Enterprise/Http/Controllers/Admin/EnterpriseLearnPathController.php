<?php

namespace App\Domains\Enterprise\Http\Controllers\Admin;

use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Enterprise\Rules\WeightlRule;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Payments\Rules\PackageSubscriptionPermission;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Enterprise\Enum\LearnPathsDeadlineType;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\User\Rules\UserPermission;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;

class EnterpriseLearnPathController extends CoreController
{
    use HasAuthorization;

    public $domain = 'enterprise';

    public function __construct(PackageSubscription $model)
    {
        $this->model = $model;


        $this->select_columns = [
            [
                'name' => trans("course::lang.name"),
                'key' => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("Deadline Type"),
                'key' => 'deadline_type',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    LearnPathsDeadlineType::STATIC => [
                        'text' => trans('Static'),
                        'class' => 'badge badge-success',
                    ],
                    LearnPathsDeadlineType::RELATIVE => [
                        'text' => trans('Relative'),
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
                    0 => [
                        'text' => trans('lang.suspended'),
                        'class' => 'badge badge-danger',
                    ],

                ]
            ],

        ];
        $this->searchColumn = ["name"];
        parent::__construct();

        $this->permitted_actions = [
            'index' => PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_INDEX,
            'create' => PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_CREATE,
            'edit' => PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_EDIT,
            'show' => null,
        ];

        $this->shareDataToViews();
    }

    public function callbackQuery($query)
    {
        return $query->whereIn('access_type',[AccessType::LEARN_PATH_SKILL , AccessType::LEARN_PATH_CAREER , AccessType::LEARN_PATH_CERTIFICATE])->where('enterprise_id', null);
    }

    public function onCreate()
    {
        parent::onCreate();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name" => ["required", "string", "max:50"],
            'deadline_type' => 'required',
            'expiration_date' => 'required_if:deadline_type,==,0|after:today',
            'expiration_days' => 'required_if:deadline_type,==,1|numeric|min:1|not_in:0',
            'activation' => ['required', 'boolean'],
            "courses" => "required|array|min:1",
            "courses.*" => "required|string|distinct|min:1",
            "weights" => ["required", "array", "min:1", new WeightlRule(1)],
            "weight.*" => "required|int|min:1",


            "sorts" => ["required", "array", "min:1"],
            "sorts.*" => "required|int|min:1",
        ]);
    }

    public function store()
    {
        $this->ifMethodExistCallIt('onStore');
        $insert = $this->model->create($this->request->all());
        $this->ifMethodExistCallIt('isStored', $insert);
//        return $this->returnMessage($insert, 1);
        if ($this->request->ajax())
            return response()->json([
                'status' => 'true',
                'message' => $this->successMessage(1, null),
                'model' => $insert,
                'url' => url("{$this->route}/{$insert->id}/edit"),
                'redirect' => true
            ]);
        return redirect("{$this->route}/{$insert->id}/edit")->with('success', $this->successMessage(1, null));

    }

    public function isStored($row)
    {
        $access_ids = $this->request->courses;
        $row->update(['access_id' => json_encode($access_ids)]);
        $row->update(['type' => SubscriptionPackageType::CUSTOM]);
        $row->update(['access_type' => $this->request->access_type]);
        $row->refresh();
//        dd($row->id);
        foreach ($access_ids as $i => $course_id) {
            CourseWeight::create([
                'package_subscription_id' => $row->id,
                'course_id' => $course_id,
                'sort' => $this->request->sorts[$i],
                'weight' => $this->request->weights[$i]
            ]);
        }
//        return redirect("{$this->route}/{$row->id}/edit?course_id={$row->course_id}&chapter_id={$row->chapter_id}")->with('success', $this->successMessage(1, null));

        if ($this->request->ajax())
            return response()->json([
                'status' => 'true',
                'message' => $this->successMessage(1, null),
                'model' => $row,
                'url' => url("{$this->route}/{$row->id}/edit?course_id={$row->course_id}&chapter_id={$row->chapter_id}"),
                'redirect' => true
            ]);


    }

    public function onEdit()
    {
        parent::onEdit();
    }

    public function onUpdate()
    {
        parent::onUpdate();
        $this->validate($this->request, [
            "name" => ["required", "string", "max:50"],
            'deadline_type' => 'required',
            'expiration_date' => 'required_if:deadline_type,==,0|after:today',
            'expiration_days' => 'required_if:deadline_type,==,1|numeric|min:1|not_in:0',

            'activation' => ['required', 'boolean'],
            "courses" => "required|array|min:1",
            "courses.*" => "required|string|distinct|min:1",

            "weights" => ["required", "array", "min:1", new WeightlRule(1)],
            "weights.*" => "required|int|min:1",


            "sorts" => ["required", "array", "min:1"],
            "sorts.*" => "required|int|min:1",
        ]);

    }

    public function isUpdated($row)
    {
        $access_ids = $this->request->courses;
        $row->update(['access_id' => json_encode($access_ids)]);
        $row->update(['access_type' => $this->request->access_type]);


        foreach ($access_ids as $index => $course_id) {
            $data[$course_id] = [
                'weight' => $this->request->weights[$index],
                'course_id' => $course_id,
                'package_subscription_id' => $row->id,
                'sort' => $this->request->sorts[$index]
            ];
            CourseWeight::updateOrCreate(
                ['package_subscription_id' => $row->id, 'course_id' => $course_id],
                [
                    'weight' => $this->request->weights[$index],
                    'course_id' => $course_id,
                    'package_subscription_id' => $row->id,
                    'sort' => $this->request->sorts[$index]
                ]
            );
        }
        CourseWeight::whereNotIn('course_id', $access_ids)->where('package_subscription_id', $row->id)->delete();
//

    }

    private function shareDataToViews()
    {
        $categories_list = CourseCategory::active()
            ->get()
            ->map(function($category) {

                if(!empty($category->cat_parent_id && isset($category->parent_category))) {
                    $name = "{$category->parent_category->name} -> {$category->name}";
                } else {
                    $name = "{$category->name}";
                }

                return [
                    'id' => $category->id,
                    'name' => $name
                ];
            })->pluck('name', 'id');
        $courses_list = Course::course()->active()->get()->pluck('internal_name_or_name', 'id');

        return view()->share(compact('categories_list', 'courses_list'));
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
        $row = EnterpriseLearnPath::find($id);
        $this->ifMethodExistCallIt('onDestroy', $row);
        $delete = $row->delete();
        $this->ifMethodExistCallIt('isDestroyed', $row);
//        return back()->with('success', $this->successMessage(3, null));
        return $this->returnMessage(true, 0, ['status' => "true"]);


    }

}
