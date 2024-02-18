<?php

namespace App\Domains\User\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use App\Domains\User\Models\Lookups\Goal;
use App\Domains\User\Rules\GoalPermission;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;

class GoalController extends CoreController
{
    use HasAuthorization;

    public $domain = "user";

    public function __construct(Goal $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.pages'), null, true);
        $this->pushBreadcrumb(trans('lang.onboarding'), null, true);

        $this->select_columns = [
            [
                'name' => trans("user::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
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
        $this->searchColumn = ["name"];        // TODO: relationable search
        parent::__construct();

        $this->permitted_actions = [
            'index'  => GoalPermission::GOAL_INDEX,
            'create' => GoalPermission::GOAL_CREATE,
            'edit'   => GoalPermission::GOAL_EDIT,
            'show'   => null,
            'delete' => GoalPermission::GOAL_DELETE,
        ];
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name"       => ["required", "string", "max:50"],
            "image"      => ["exists:uploads"],
            'activation' => ['required', 'boolean']
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"       => ["required", "string", "max:50"],
            "image"      => ["exists:uploads"],
            'activation' => ['required', 'boolean']
        ]);
    }

}
