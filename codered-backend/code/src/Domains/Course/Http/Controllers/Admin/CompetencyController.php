<?php

namespace App\Domains\Course\Http\Controllers\Admin;

use App\Domains\Course\Models\Competency;
use App\Domains\Course\Rules\CompetencyPermission;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Course\Enum\CourseCategoryColors;
use App\Domains\Course\Models\JobRole;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Domains\Course\Models\SpecialtyArea;
use App\Domains\Course\Rules\SpecialtyAreaPermission;

class CompetencyController extends CoreController
{
    use HasAuthorization;

    public $domain = "course";

    public function __construct(Competency $model)
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
            'index'  => CompetencyPermission::COMPETENCY_INDEX,
            'create' => CompetencyPermission::COMPETENCY_CREATE,
            'edit'   => CompetencyPermission::COMPETENCY_EDIT,
            'delete' => CompetencyPermission::COMPETENCY_DELETE,
        ];
    }

    public function onCreate()
    {
        parent::onCreate();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name"       => ["required", "string", "max:50", "unique:{$this->model->getTable()}"],
            'activation' => ['required', 'boolean'],
        ]);
    }

    public function onEdit()
    {
        parent::onEdit();
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"       => ["required", "string", "max:50",  "unique:{$this->model->getTable()},". "name,{$this->request->competency}"],
            'activation' => ['required', 'boolean'],
        ]);
    }

}
