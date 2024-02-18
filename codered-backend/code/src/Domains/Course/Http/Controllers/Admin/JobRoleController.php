<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Enum\CourseCategoryColors;
use App\Domains\Course\Models\JobRole;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\Course\Rules\JobRolePermission;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Foundation\Http\Controllers\Admin\CoreController;

class JobRoleController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(JobRole $model)
    {
        $this->model = $model;

        /*$this->pushBreadcrumb(trans('lang.settings'), null, true);
        $this->pushBreadcrumb(trans('lang.lookups'), null, true);*/


        $this->select_columns = [

            [
                'name' => trans("course::lang.name"),
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
        $this->searchColumn = ["name"];

        parent::__construct();

        $this->permitted_actions = [
            'index'  => JobRolePermission::JOB_ROLE_INDEX,
            'create' => JobRolePermission::JOB_ROLE_CREATE,
            'edit'   => JobRolePermission::JOB_ROLE_EDIT,
            'delete' => JobRolePermission::JOB_ROLE_DELETE,
        ];
    }

    // public function callbackQuery() {
    //     $parent_id = $this->request->query('parent_id');

    //     if(!empty($parent_id)) {
    //        return CourseCategory::where('cat_parent_id', $parent_id);
    //     } else {
    //         return CourseCategory::parent();
    //     }
    // }

    public function onCreate()
    {
        parent::onCreate();
        $this->sharedViews();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name"        => ["required", "string", "max:50", "unique:{$this->model->getTable()}"],
            'activation'  => ['required', 'boolean'],
        ]);


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
            "name"        => ["required", "string", "max:50", "unique:{$this->model->getTable()},name,{$this->request->job_role}"],
            'activation'  => ['required', 'boolean'],
        ]);
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
    }

    public function searchJobRoles ()
    {
        $search = $this->request->get('search');
        // get all active job roles
        $job_roles = JobRole::where('name', 'like', "%{$search}%")->active()->get()->map(function($row) {
            return [
                'id' => $row->id,
                'text' => $row->name
            ];
        });
        return $job_roles;
    }


}
