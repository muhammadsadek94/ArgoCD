<?php

namespace App\Domains\Admin\Http\Controllers\Admin;

use App\Domains\Admin\Models\Role;
use App\Domains\Admin\Models\Admin;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Rules\NameRule;
use App\Foundation\Rules\PasswordRule;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Admin\Rules\AdminPermission;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Http\Controllers\Admin\CoreController;

class AdminController extends CoreController
{

    use HasAuthorization;

    public $domain = "admin";

    public function __construct(Admin $model)
    {
        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("admin::lang.image"),
                'key'  => 'image',
                'type' => ColumnTypes::IMAGE,
            ],
            [
                'name' => trans("admin::lang.name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("admin::lang.email"),
                'key'  => 'email',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("admin::lang.role"),
                'type' => ColumnTypes::CALLBACK,
                'key'  => function($row) {
                    return $row->role->name ?? null;
                }
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
            [
                'name'         => trans("admin::lang.status"),
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
        $this->searchColumn = ["name", "email"];        // TODO: relationable search
        parent::__construct();
    }

    public function callbackQuery($query)
    {
        return $query->where('is_super_admin', 0);
    }

    public function onIndex()
    {
        $this->hasPermission(AdminPermission::ADMIN_INDEX);
    }

    public function onCreate()
    {
        $this->hasPermission(AdminPermission::ADMIN_CREATE);

        $roles_list = Role::pluck('name', 'id');
        view()->share(compact('roles_list'));

    }

    public function onEdit()
    {
        $this->hasPermission(AdminPermission::ADMIN_EDIT);

        $roles_list = Role::pluck('name', 'id');
        view()->share(compact('roles_list'));

    }

    public function onDestroy()
    {
        $this->hasPermission(AdminPermission::ADMIN_DELETE);

    }

    public function onStore()
    {
        $this->hasPermission(AdminPermission::ADMIN_CREATE);

        $this->validate($this->request, [
            "name"       => ["required", "string", "max:50"],
            "email"      => ["required", "unique:admins"],
            "password"   => ["required", new PasswordRule, 'min:10'],
            'activation' => ['required', 'boolean']
        ]);
    }

    public function onUpdate()
    {
        $this->hasPermission(AdminPermission::ADMIN_EDIT);

        $this->validate($this->request, [
            "name"       => ["required", "string", "max:50"],
            "email"      => "required|email|unique:admins,email,{$this->request->admin}|max:255",
            'activation' => ['required', 'boolean'],
        ]);
    }

    public function patchUpdatePassword(Admin $admin)
    {
        $this->hasPermission(AdminPermission::ADMIN_EDIT);

        $this->validate($this->request, [
            "password" => ["required",  new PasswordRule, 'min:10']
        ]);

        $admin->update(request(['password']));

        return response()->json([
            'status'  => 'true',
            'message' => $this->successMessage(2, null)
        ]);

    }

    public function postUploadPicture()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

}
