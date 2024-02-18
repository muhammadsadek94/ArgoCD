<?php

namespace App\Domains\Bundles\Http\Controllers\Admin;

use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Models\Course;
use App\Domains\Bundles\Rules\CourseBundlePermission;
use App\Domains\Bundles\Models\CourseBundle;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Http\Controllers\Admin\CoreController;

class CourseBundleController extends CoreController
{

    use HasAuthorization;

    public $domain = "bundles";

    public function __construct(CourseBundle $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.bundle'), null, true);

        $this->select_columns = [
            [
                'name' => trans("bundles::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("bundles::lang.description"),
                'key'  => 'description',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name'         => trans("bundles::lang.status"),
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
            'index'  => CourseBundlePermission::COURSE_BUNDLE_INDEX,
            'create' => CourseBundlePermission::COURSE_BUNDLE_CREATE,
            'edit'   => CourseBundlePermission::COURSE_BUNDLE_EDIT,
            'show'   => null,
            'delete' => CourseBundlePermission::COURSE_BUNDLE_DELETE,
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
            "name"            => ["required", "string", "max:50"],
            "description"     => ["required"],
            "features"        => ["required", "max:50"],
            "payment_title"   => ["required","max:50"],
            "price"           => ["required"],
            'bundle_url'      => 'required|url',
            'access_pass_url' => 'required|url',
            'price_features'  => ["required", "max:50"],
            "image_id"        => ['required', "exists:uploads,id"],
            "cover_image_id"  => ['required', "exists:uploads,id"],
            'activation'      => ['required', 'boolean'],
        ]);
    }

    public function isStored($row)
    {


        if($this->request->access_type == AccessType::COURSES) {
            $access_ids = $this->request->courses;
            $row->update(['access_id' => $access_ids]);

        }

        if($this->request->access_type == AccessType::COURSE_CATEGORY) {
            $access_ids = $this->request->categories;
            $row->update(['access_id' => $access_ids]);

        }

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
            "name"            => ["required", "string", "max:50"],
            "description"     => ["required"],
            "features"        => ["required", "max:50"],
            "payment_title"   => ["required","max:50"],
            "price"           => ["required"],
            'bundle_url'      => 'required|url',
            'access_pass_url' => 'required|url',
            'price_features'  => ["required", "max:50"],
            "image_id"        => ['required', "exists:uploads,id"],
            "cover_image_id"  => ['required', "exists:uploads,id"],
            'activation'      => ['required', 'boolean'],
        ]);
    }

    public function isUpdated($row)
    {


        if($this->request->access_type == AccessType::COURSES) {
            $access_ids = $this->request->courses;
            $row->update(['access_id' => $access_ids]);

        }

        if($this->request->access_type == AccessType::COURSE_CATEGORY) {
            $access_ids = $this->request->categories;
            $row->update(['access_id' => $access_ids]);

        }


    }


    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }



    public function sharedViews()
    {

        $categories_list = CourseCategory::pluck('name', 'id');
        $courses_list = Course::course()->get()->pluck('internal_name_or_name', 'id');
        $packages = PackageSubscription::active()->where('type',SubscriptionPackageType::CUSTOM)->pluck('name', 'id');

        return view()->share(compact('categories_list', 'packages','courses_list'));

    }









}
