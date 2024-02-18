<?php

namespace App\Domains\Partner\Http\Controllers\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Partner\Models\Partner;
use App\Domains\Partner\Rules\PartnerPermissions;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Rules\NameRule;
use App\Foundation\Traits\HasAuthorization;
use Hash;
use Illuminate\Http\Request;

class PartnerController extends CoreController
{
    use HasAuthorization;

    public $domain = "partner";

    public function __construct(Partner $model)
    {
        $this->model = $model;
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
                    1 => [
                        'text'  => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    0 => [
                        'text'  => 'Deactivated',
                        'class' => 'badge badge-danger',
                    ],

                ]
            ],
        ];
        $this->searchColumn = ["name"];

        $this->orderBy = null;
        parent::__construct();

        $this->permitted_actions = [
            'index'  => PartnerPermissions::PARTNER_INDEX,
            'create' => PartnerPermissions::PARTNER_CREATE,
            'edit'   => PartnerPermissions::PARTNER_EDIT,
            'show'   => null,
            'delete' => PartnerPermissions::PARTNER_DELETE,
        ];

    }


    public function index()
    {
        $this->pushBreadcrumb(trans('lang.index'), null, true);

        $this->ifMethodExistCallIt('onIndex');
        $this->request->flash();
        $search = $this->request->search;

        $rows = $this->model;

        if(!is_null($this->orderBy)) {
            $rows = $rows->orderBy($this->orderBy[0], $this->orderBy[1]);
        }

        if(!empty($search)) {
            $rows->where(function ($rows) use( $search){
                foreach($this->searchColumn as $key => $column) {
                    if($key == 0) {
                        $rows = $rows->where($column, "LIKE", "%{$search}%");
                    } else {
                        $rows = $rows->orWhere($column, "LIKE", "%{$search}%");
                    }

                }
            });

        }

        if(method_exists($this, 'callbackQuery')) {
            $rows = $this->callbackQuery($rows);
        }

        $rows = $rows->paginate($this->perPage);

        if($this->request->ajax())
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

        $this->shareViews();

    }

    public function onEdit()
    {
        parent::onEdit();

        $this->shareViews();
    }


    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name" => ["required", "string", "max:50", new NameRule],
            "partner_name"      => ["unique:partners,partner_name", 'between:6,18'],
            "partner_secret"      => ["required", "min:25", "max:100"],
            "activation"   => ["required", "min:1", "max:1"],
            'course_ids' => ['array'],
            'course_ids.*' => ['required', "exists:courses,id"],
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name" => ["required", "string", "max:50", new NameRule],
            "partner_name"      => ["unique:partners,partner_name,". $this->request->partner, 'between:6,18'],
            "partner_secret"      => ["min:25", "max:100"],
            "activation"   => ["required", "min:1", "max:1"],
            'course_ids' => ['array'],
            'course_ids.*' => ['required', "exists:courses,id"],
        ]);
    }

    public function isStored($row)
    {
        $row->courses()->sync($this->request->course_ids);
    }

    public function isUpdated($row)
    {
        $row->courses()->sync($this->request->course_ids);
    }

    public function getCourse()
    {
        $search = $this->request->search;


        $select_options = Course::where('name', 'LIKE', "%{$search}%")
            ->active()
            ->get()->map(function($row) {
                return [
                    'id' => $row->id,
                    'text' => $row->name
                ];
            });

        return $select_options;

    }


    private function shareViews()
    {
        $courses_list = Course::course()->active()->get()->pluck('internal_name_or_name', 'id');

        view()->share(compact('courses_list'));
    }


}
