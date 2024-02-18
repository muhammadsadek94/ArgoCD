<?php

namespace App\Domains\Admin\Http\Controllers\Admin;

use App\Domains\Admin\Models\Role;
use App\Foundation\Enum\ColumnTypes;
use App\Domains\Admin\Models\Module;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Admin\Rules\RolesPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;

class RoleController extends CoreController
{

    use HasAuthorization;

    public $domain = "admin";

    public function __construct(Role $model)
    {
        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("admin::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name'         => trans("admin::lang.is_super_admin"),
                'key'          => 'is_super_admin',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    1 => [
                        'text'  => trans('lang.yes'),
                        'class' => 'badge badge-success',
                    ],
                    0 => [
                        'text'  => trans('lang.no'),
                        'class' => 'badge badge-danger',
                    ],

                ]
            ],

        ];
        $this->searchColumn = ["name"];        // TODO: relationable search
        parent::__construct();
    }

    public function callbackQuery($query)
    {
        return $query->where('is_super_admin', 0);
    }

    public function onIndex()
    {
        $this->hasPermission(RolesPermission::ROLE_INDEX);
    }

    public function onCreate()
    {
        $this->hasPermission(RolesPermission::ROLE_CREATE);

        $module_roles = Module::with("permissions")->orderBy('created_at', 'asc')->get();

        return view()->share(compact('module_roles'));

    }

    public function onStore()
    {
        $this->hasPermission(RolesPermission::ROLE_CREATE);

        $this->validate($this->request, [
            "name"      => ["required", "string", "max:50", 'unique:roles'],
            "ability"   => "array",
            "ability.*" => "exists:abilities,id"
        ]);
    }

    public function isStored(Role $role)
    {
        $role->allow($this->request->ability);
    }

    public function onEdit()
    {
        $this->hasPermission(RolesPermission::ROLE_EDIT);

        $module_roles = Module::with("permissions")->orderBy('created_at', 'asc')->get();

        return view()->share(compact('module_roles'));

    }

    public function onUpdate()
    {
        $this->hasPermission(RolesPermission::ROLE_EDIT);

        $this->validate($this->request, [
            "name"      => ["required", "string", "max:50", "unique:roles,id,{$this->request->role}"],
            "ability"   => "array",
            "ability.*" => "exists:abilities,id"
        ]);
    }

    public function isUpdated(Role $role)
    {
        $role->allow($this->request->ability);
    }

    public function onDestroy()
    {
        $this->hasPermission(RolesPermission::ROLE_DELETE);

    }

}
