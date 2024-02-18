<?php

namespace App\Domains\Geography\Http\Controllers\Admin;

use App\Foundation\Traits\HasAuthorization;
use App\Domains\Geography\Enum\ActivationTypes;
use App\Domains\Geography\Models\Area;
use App\Domains\Geography\Models\City;
use App\Foundation\Enum\ColumnTypes;
use App\Domains\Geography\Rules\AreaPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;

class AreaController extends CoreController
{
    use HasAuthorization;

    public $domain = 'geography';

    public function __construct(Area $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.settings'), null, true);
        $this->pushBreadcrumb(trans('geography::lang.geography'), null, true);

        $this->select_columns = [
            [
                'name' => trans("geography::lang.name_en"),
                'key'  => 'name_en',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("geography::lang.name_ar"),
                'key'  => 'name_ar',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name'         => trans("geography::lang.activation"),
                'key'          => 'activation',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    ActivationTypes::ACTIVE   => [
                        'text'  => trans('geography::lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    ActivationTypes::INACTIVE => [
                        'text'  => trans('geography::lang.suspended'),
                        'class' => 'badge badge-danger',
                    ],
                ]
            ],
        ];
        $this->searchColumn = ["name_en", "name_ar"];

        $this->permitted_actions = [
            'index'  => AreaPermission::AREA_INDEX,
            'create' => AreaPermission::AREA_CREATE,
            'edit'   => AreaPermission::AREA_EDIT,
            'delete' => AreaPermission::AREA_DELETE,
        ];

        parent::__construct();

    }

    public function onCreate()
    {
        parent::onCreate();

        view()->share([
            'cities_list' => config('app.locale') == 'en' ? City::pluck("name_en", "id") : City::pluck("name_ar", "id")
        ]);
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name_en"    => ["required", "max:255"],
            "name_ar"    => ["required", "max:255"],
            "city_id"    => ["required", "exists:cities,id"],
            "activation" => ["required", "boolean"],
        ]);
    }

    public function onEdit()
    {
        parent::onEdit();

        view()->share([
            'cities_list' => config('app.locale') == 'en' ? City::pluck("name_en", "id") : City::pluck("name_ar", "id")
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name_en"    => ["required", "max:255"],
            "name_ar"    => ["required", "max:255"],
            "city_id"    => ["required", "exists:cities,id"],
            "activation" => ["required", "boolean"],
        ]);
    }

}
