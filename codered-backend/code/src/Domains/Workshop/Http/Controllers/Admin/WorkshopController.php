<?php

namespace App\Domains\Workshop\Http\Controllers\Admin;

use App\Domains\User\Models\User;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Workshop\Models\Workshop;
use App\Domains\Workshop\Rules\WorkshopPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;

class WorkshopController extends CoreController
{
    use HasAuthorization;

    public $domain = "workshop";

    public function __construct(Workshop $model)
    {
        $this->model = $model;

        $this->select_columns = [
            [
                'name' => 'Title',
                'key'  => 'title',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => 'Instructor',
                'type' => ColumnTypes::CALLBACK,
                'key'  => function($row) {
                    return $row->user->first_name ?? null;
                },
            ],
            [
                'name' => 'Date',
                'key'  => 'date',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name'         => 'Type',
                'key'          => 'type',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text'  => 'Upcoming',
                        'class' => 'badge badge-success',
                    ],
                    2 => [
                        'text'  => 'Recorded',
                        'class' => 'badge badge-danger',
                    ],

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
            ]
        ];
        
        $this->searchColumn = ["title", "description", "date", "time"];

        $this->permitted_actions = [
            'index'  => WorkshopPermission::WORKSHOP_INDEX,
            'create' => WorkshopPermission::WORKSHOP_CREATE,
            'edit'   => WorkshopPermission::WORKSHOP_EDIT,
            'delete' => WorkshopPermission::WORKSHOP_DELETE,
        ];

        parent::__construct();


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
            foreach($this->searchColumn as $key => $column) {
                if($key == 0) {
                    $rows = $rows->where($column, "LIKE", "%{$search}%")
                            ->orWhereHas('user', function($query) use($search){
                                $query->where('first_name', "LIKE", "%{$search}%")
                                ->orWhere('last_name', "LIKE", "%{$search}%");
                            });
                } else {
                    $rows = $rows->orWhere($column, "LIKE", "%{$search}%");
                }
            }
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

    public function onEdit()
    {
        parent::onEdit();
        $users = User::active()->provider()->pluck('first_name', 'id');
        return view()->share(compact('users'));
    }

    public function onCreate()
    {
        parent::onCreate();
        $users = User::active()->provider()->pluck('first_name', 'id');
        return view()->share(compact('users'));
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "title"                 => ["required", "string", "max:200"],
            "description"           => ["required", "string", "max:250"],
            "user_id"               => ["required", "exists:users,id"],
            "image_id"              => ["required"],
            'date'                  => ['required', 'date'],
            'time'                  => ['required'],
            'link'                  => ['required', 'url'],
            'type'                  => ['required'],
            'activation'            => ['required', 'boolean']
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "title"                 => ["required", "string", "max:200"],
            "description"           => ["required", "string", "max:250"],
            "user_id"               => ["required", "exists:users,id"],
            "image_id"              => ["required"],
            'date'                  => ['required', 'date'],
            'time'                  => ['required'],
            'link'                  => ['required', 'url'],
            'type'                  => ['required'],
            'activation'            => ['required', 'boolean']
        ]);
    }

    public function postUploadPicture()
    {
        $this->hasPermission(WorkshopPermission::WORKSHOP_EDIT);

        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }


}
