<?php

namespace App\Domains\Geography\Http\Controllers\Admin;

use App\Domains\Geography\Models\Country;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Geography\Enum\ActivationTypes;
use App\Domains\Geography\Models\City;
use App\Foundation\Enum\ColumnTypes;
use App\Domains\Geography\Rules\CityPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;

class CityController extends CoreController
{

    use HasAuthorization;

    public $domain = 'geography';

    public function __construct(City $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.settings'), null, false);
        $this->pushBreadcrumb(trans('geography::lang.geography'), null, false);

        $this->select_columns = [
            [
                'name' => trans("geography::lang.name_en"),
                'key'  => 'name_en',
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
            'index'  => CityPermission::CITY_INDEX,
            'create' => CityPermission::CITY_CREATE,
            'edit'   => CityPermission::CITY_EDIT,
            'delete' => CityPermission::CITY_DELETE,
        ];
        parent::__construct();

    }




    public function onCreate()
    {
        parent::onCreate();

        view()->share([
            'countries_list' => config('app.locale') == 'en' ? Country::active()->pluck("name_en", "id") : Country::active()->pluck("name_ar", "id")
        ]);
    }


    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name_en"    => ["required", "max:255"],
//            "name_ar"    => ["required", "max:255"],
            "activation" => ["required", "boolean"],
            "country_id" => ["required", "exists:countries,id"],
        ]);
    }


    public function onEdit()
    {
        parent::onEdit();

        view()->share([
            'countries_list' => config('app.locale') == 'en' ? Country::active()->pluck("name_en", "id") : Country::active()->pluck("name_ar", "id")
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name_en"    => ["required", "max:255"],
//            "name_ar"    => ["required", "max:255"],
            "activation" => ["required", "boolean"],
            "country_id" => ["required", "exists:countries,id"],
        ]);
    }

}
