<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\ProctorUsers;
use App\Domains\Course\Rules\ProctorPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Traits\HasAuthorization;
use ColumnTypes;

class ProctorUserController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(ProctorUsers $model)
    {
        $this->model = $model;

        $this->select_columns = [
            [
                'name' => trans("Username"),
                'key'  => 'username',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Courses"),
                'key'  => function ($row) {
                    $courses = Course::whereIn('id', $row->course_ids)->pluck('name')->implode(', ');
                    return $courses;
                },
                'type' => ColumnTypes::CALLBACK,
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
                        'text'  => 'Deactivated',
                        'class' => 'badge badge-danger',
                    ],
                ]
            ]
        ];
        $this->searchColumn = ["username"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => ProctorPermission::PROCTOR_INDEX,
            'create' => ProctorPermission::PROCTOR_CREATE,
            'edit'   => ProctorPermission::PROCTOR_EDIT,
            'delete' => ProctorPermission::PROCTOR_DELETE,
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

        $this->validate($this->request, [
            "username"   => ["required", "string", 'min:4', "max:100", 'unique:proctor_users'],
            'activation' => ['required', 'boolean'],
            'password'   => ['required','min:6'],
            'course_ids' => ['required']
        ]);

    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "username"   => ["required", "string", 'min:4', "max:100", 'unique:proctor_users,username,' . $this->request->route('proctor_user')],
            'activation' => ['required', 'boolean'],
            'password'   => ['min:6'],
            'course_ids' => ['required']
        ]);

    }

    private function sharedFormData()
    {
        $courses_list = Course::Microdegrees()->get()->pluck('internal_name_or_name', 'id');
        $activation_list = [
            1 => trans('lang.active'),
            0 => 'Deactivate'
        ];
        return view()->share(compact('courses_list', 'activation_list'));
    }

}
