<?php

namespace App\Domains\Geography\Http\Controllers\Admin;

use App\Foundation\Traits\HasAuthorization;
use App\Domains\Geography\Enum\ActivationTypes;
use App\Domains\Geography\Models\Country;
use App\Foundation\Enum\ColumnTypes;
use App\Domains\Geography\Rules\CountryPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;


class CountryController extends CoreController
{

    public $domain = 'geography';

    public function __construct(Country $model)
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
//            [
//                'name' => trans("geography::lang.phone_code"),
//                'key'  => 'phone_code',
//                'type' => ColumnTypes::STRING,
//            ],
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
        $this->searchColumn = ["name_ar", "name_en"];

        $this->permitted_actions = [
            'index'  => CountryPermission::COUNTRY_INDEX,
            'create' => CountryPermission::COUNTRY_CREATE,
            'edit'   => CountryPermission::COUNTRY_EDIT,
            'delete' => CountryPermission::COUNTRY_DELETE,
        ];

        parent::__construct();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name_en"            => ["required", 'max:255'],
//            "name_ar"            => ["required", 'max:255'],
//            "iso"                => ["required", 'max:255'],
//            "phone_code"         => ["required", 'integer'],
//            "number_allow_digit" => ["required", 'max:999999', 'integer', 'regex:/^[0-99]/'],
//            "currency_name_en"   => ["required", 'max:255'],
//            "currency_symbol"    => ["required", 'max:255'],
//            "currency_name_ar"   => ["required", 'max:255'],
//            "image"              => ["required", "exists:uploads,id"],
            "activation"         => ["required", 'max:1'],
//            "show_nationality"   => ['boolean'],
//            "show_country"       => ['boolean'],
        ]);
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name_en"            => ["required", 'max:255'],
//            "name_ar"            => ["required", 'max:255'],
//            "iso"                => ["required", 'max:255'],
//            "phone_code"         => ["required", 'max:99999999', 'integer'],
//            "number_allow_digit" => ["required", 'max:999999', 'integer'],
//            "currency_name_en"   => ["required", 'max:255'],
//            "currency_symbol"    => ["required", 'max:255'],
//            "currency_name_ar"   => ["required", 'max:255'],
//            "image"              => ["exists:uploads,id"],
            "activation"         => ["required", 'boolean'],
//            "show_nationality"   => ['boolean'],
//            "show_country"       => ['boolean'],
        ]);
    }

}
