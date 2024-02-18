<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Enum\CourseCategoryColors;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\Course\Rules\CourseCategoryPermission;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Foundation\Http\Controllers\Admin\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CourseCategoryController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(CourseCategory $model)
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
            /* [
                'name' => trans("Name 2"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],*/

            [
                'name' => trans("course::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => 'Sub Categories',
                'type' => ColumnTypes::CALLBACK,
                'key'  => function ($row) {
                    if (empty($row->cat_parent_id))
                        return "<a href='" . url('/admin/course-category?parent_id=' . $row->id) . "'><span class='badge badge-primary'>has {$row->sub_categories->count()} sub categories</span></a>";
                    else
                        return "<span class='badge badge-primary'>is a sub categories</span>";
                },
            ],


            [
                'name'         => trans("Status"),
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
            'index'  => CourseCategoryPermission::COURSE_CATEGORY_INDEX,
            'create' => CourseCategoryPermission::COURSE_CATEGORY_CREATE,
            'edit'   => CourseCategoryPermission::COURSE_CATEGORY_EDIT,
            'show'   => null,
            'delete' => CourseCategoryPermission::COURSE_CATEGORY_DELETE,
        ];
    }

    public function callbackQuery($rows)
    {
        $parent_id = $this->request->query('parent_id');

        if (!empty($parent_id)) {
            return CourseCategory::where('cat_parent_id', $parent_id);
        } else {
            return $rows->parent();
        }
    }

    public function index()
    {
        $this->pushBreadcrumb(trans('lang.index'), null, true);

        $this->ifMethodExistCallIt('onIndex');
        $this->request->flash();
        $search = $this->request->search;

        $rows = $this->model;

        if (!is_null($this->orderBy)) {
            $rows = $rows->orderBy($this->orderBy[0], $this->orderBy[1]);
        }

        if (!empty($search)) {
            $rows->where(function ($rows) use ($search) {

                foreach ($this->searchColumn as $key => $column) {
                    if ($key == 0) {
                        $rows = $rows->where($column, "LIKE", "%{$search}%")
                            ->orwhereHas('sub_categories', function ($query) use ($search) {
                                $query->where('name', 'LIKE', "%{$search}%");
                            });
                    } else {
                        $rows = $rows->orWhere($column, "LIKE", "%{$search}%")
                            ->orwhereHas('sub_categories', function ($query) use ($search) {
                                $query->where('name', 'LIKE', "%{$search}%");
                            });
                    }
                }
            });
        }

        if (method_exists($this, 'callbackQuery')) {
            $rows = $this->callbackQuery($rows);
        }

        $rows = $rows->paginate($this->perPage);

        if ($this->request->ajax())
            return response()->json($this->view('loop', [
                'rows'           => $rows,
                'select_columns' => $this->select_columns,
            ])->render());

        return $this->view('index', [
            'rows'           => $rows,
            'select_columns' => $this->select_columns,
            'breadcrumb'     => $this->breadcrumb,
        ]);
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
            "name"        => ["required", "string", "max:50"],
            "image"       => ["exists:uploads"],
            'activation'  => ['required', 'boolean'],
            'sort'        => ['nullable', 'integer', 'min:0', 'unique:course_categories,sort'],
        ]);

        if (empty($this->request->cat_parent_id))
            $this->request->request->remove('cat_parent_id');

        if (empty($this->request->sort))
            $this->request->request->remove('sort');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response|Voild
     */
    public function update($id)
    {
        if (empty($this->request->cat_parent_id))
            $this->request->request->remove('cat_parent_id');

        $data = $this->request->all();
        $data['cat_parent_id'] = $this->request->cat_parent_id == "0" ? null : $this->request->cat_parent_id;
        $data['sort'] = $this->request->sort == 0 ? null : $this->request->sort;
        $this->ifMethodExistCallIt('onUpdate');
        $row = $this->model->find($id);
        $update = $row->update($data);
        $this->ifMethodExistCallIt('isUpdated', $row);
        return $this->returnMessage($update, 2);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response|Voild
     */
    public function store()
    {
        if (empty($this->request->cat_parent_id))
            $this->request->request->remove('cat_parent_id');
            
        $data = $this->request->all();
        $data['cat_parent_id'] = $this->request->cat_parent_id == "0" ? null : $this->request->cat_parent_id;
        $data['sort'] = $this->request->sort == 0 ? null : $this->request->sort;
        $this->ifMethodExistCallIt('onStore');
        $insert = $this->model->create($data);
        $this->ifMethodExistCallIt('isStored', $insert);
        return $this->returnMessage($insert, 1);
    }
    public function onEdit()
    {
        parent::onEdit();
        $this->sharedViews();
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $row = $this->model->find($this->request->id);

        $this->validate($this->request, [
            "name"        => ["required", "string", "max:50"],
            "image"       => ["exists:uploads"],
            'activation'  => ['required', 'boolean'],
            'sort'        => ['nullable', 'integer', 'min:0', 'unique:course_categories,sort,' . $this->request->course_category],
        ], $this->sortMessage($row));

        if (empty($this->request->sort))
            $this->request->request->remove('sort');
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
        $colors_lists = CourseCategoryColors::getList();
        $categories = CourseCategory::active()->parent()->pluck('name', 'id')->prepend('select parent category', null);

        return view()->share(compact('colors_lists', 'categories'));
    }

    public function get_categories(Request $request)
    {
        $category_id = $request->query('category_id');
        $categories = CourseCategory::where('cat_parent_id', $category_id)->get()->map(function ($row) {
            return [
                'id'   => $row->id,
                'text' => $row->name,
            ];
        });

        return $categories;
    }

    public function sortMessage($row)
    {
        $existing_sub_category = $this->model->where('sort', $this->request->sort)->first();
        if (!empty($existing_sub_category)) {
            return [
                'sort.unique' => 'The sort has already been taken by ' . $existing_sub_category->name,
            ];
        }

        return [];
    }
}
