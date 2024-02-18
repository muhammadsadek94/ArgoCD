<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use Illuminate\Support\Facades\Request;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Models\Lookups\CourseTag;
use App\Domains\Course\Rules\CourseTagPermission;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Foundation\Http\Controllers\Admin\CoreController;
use Form;

class CourseTagController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(CourseTag $model)
    {
        $this->model = $model;

        /*$this->pushBreadcrumb(trans('lang.settings'), null, true);
        $this->pushBreadcrumb(trans('lang.lookups'), null, true);*/

        $this->pushBreadcrumb(trans('lang.pages'), null, true);
        $this->pushBreadcrumb(trans('lang.onboarding'), null, true);

        $this->select_columns = [
            //            [
            //                'name' => trans("course::lang.image"),
            //                'key'  => 'image',
            //                'type' => ColumnTypes::IMAGE,
            //            ],
            [
                'name' => trans("course::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => 'is featured',
                'key'          => 'is_featured',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text'  => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    0 => [
                        'text'  => 'in active',
                        'class' => 'badge badge-danger',
                    ],

                ]
            ],
            [
                'name' => trans("course::lang.Course Category"),
                'type' => ColumnTypes::CALLBACK,
                'key'  => function($row) {
                    return $row->category->name ?? null;
                },
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
            ]
        ];
        $this->searchColumn = ["name"];

        $this->permitted_actions = [
            'index'  => CourseTagPermission::COURSE_TAG_INDEX,
            'create' => CourseTagPermission::COURSE_TAG_CREATE,
            'edit'   => CourseTagPermission::COURSE_TAG_EDIT,
            'show'   => null,
            'delete' => CourseTagPermission::COURSE_TAG_DELETE,
        ];

        parent::__construct();


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
            "name"               => ["required", "string", "max:50"],
            //            "image"              => ["exists:uploads"],
            "course_category_id" => ["required", "exists:course_categories,id"],
            'activation'         => ['required', 'boolean']
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"               => ["required", "string", "max:50"],
            //            "image"              => ["exists:uploads"],
            "course_category_id" => ["required", "exists:course_categories,id"],
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
        $categories_list = CourseCategory::active()
        ->parent()
        ->get()
        // ->map(function($category) {

        //     if(!empty($category->cat_parent_id)) {
        //         $name = "{$category->parent_category->name} -> {$category->name}";
        //     } else {
        //         $name = "{$category->name}";
        //     }

        //     return [
        //         'id' => $category->id,
        //         'name' => $name
        //     ];
        // })
        ->pluck('name', 'id');

        return view()->share(compact('categories_list'));
    }

    public function searchTagsByCategory()
    {
        $category_id = $this->request->category_id;
        $search = $this->request->search;

        $select_options = $this->model;
        if($category_id) {
            $select_options = $select_options->where('course_category_id', $category_id);
        }

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
