<?php

namespace App\Domains\User\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use Illuminate\Support\Facades\Request;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\User\Rules\UserTagPermission;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Domains\Geography\Models\Country;
use Form;

class UserTagController extends CoreController
{
    use HasAuthorization;

    public $domain = "user";

    public function __construct(UserTag $model)
    {
        $this->model = $model;



        $this->select_columns = [
            [
                'name' => trans("user::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name'         => trans("user::lang.status"),
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
            ]
        ];
        $this->searchColumn = ["name"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => UserTagPermission::USER_TAG_INDEX,
            'create' => UserTagPermission::USER_TAG_CREATE,
            'edit'   => UserTagPermission::USER_TAG_EDIT,
            'show'   => null,
            'delete' => UserTagPermission::USER_TAG_DELETE,
        ];

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
        $this->request->request->add(['type' => 0]); // to show to CodeRed Admin
        $this->validate($this->request, [
            "name"               => ["required", "string", "max:50"],
            //            "image"              => ["exists:uploads"],
            /*"user_category_id" => ["required", "exists:course_categories,id"],*/
            'activation'         => ['required', 'boolean']
        ]);

    }

    public function onUpdate()
    {
        parent::onUpdate();
        $this->request->request->add(['type' => 0]); // to show to CodeRed Admin

        $this->validate($this->request, [
            "name"               => ["required", "string", "max:50"],
            //            "image"              => ["exists:uploads"],
           /* "course_category_id" => ["required", "exists:course_categories,id"],*/
            'activation'         => ['required', 'boolean']
        ]);
    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

    private function sharedFormData()
    {
        //$categories_list = CourseCategory::active()->pluck('name', 'id');
        $country_lists = Country::active()->pluck('name_en', 'id');
        //return view()->share(compact('categories_list'));
        return view()->share(compact('country_lists'));
    }

    public function searchTagsByCategory()
    {

        $search = $this->request->search;

        $select_options = $this->model;

        $select_options = $select_options->where('name', 'LIKE', "%{$search}%")
            ->active()
            ->get()->map(function($row) {
                return [
                    'id' => $row->id,
                    'text' => $row->name
                ];
            });

        return $select_options;

    }

}
