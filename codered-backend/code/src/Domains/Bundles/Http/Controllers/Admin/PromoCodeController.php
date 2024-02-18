<?php

namespace App\Domains\Bundles\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Bundles\Rules\PromoCodePermission;
use App\Domains\Bundles\Models\PromoCode;
use App\Domains\Bundles\Models\CourseBundle;
use App\Foundation\Http\Controllers\Admin\CoreController;

class PromoCodeController extends CoreController
{
    
    use HasAuthorization;

    public $domain = "bundles";

    public function __construct(PromoCode $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.bundle'), null, true);
        
        $this->select_columns = [
            [
                'name' => trans("bundles::lang.heading"),
                'key'  => 'heading',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("bundles::lang.coupon_code"),
                'key'  => 'coupon_code',
                'type' => ColumnTypes::STRING,
            ],

            /*[
                'name' => trans("bundles::lang.course_bundle"),
                'key'  => function ($row) {
                    $bundle = CourseBundle::where('id', $row->course_bundle_id)->select('name')->first();
                    return $bundle['name'];
                },
                'type' => ColumnTypes::CALLBACK,
            ],*/

            [
                'name'         => trans("bundles::lang.status"),
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
        $this->searchColumn = ["heading"];
        parent::__construct();

      

        $this->permitted_actions = [
            'index'  => PromoCodePermission::PROMO_CODE_INDEX,
            'create' => PromoCodePermission::PROMO_CODE_CREATE,
            'edit'   => PromoCodePermission::PROMO_CODE_EDIT,
            'show'   => null,
            'delete' => PromoCodePermission::PROMO_CODE_DELETE,
        ];
    }

    public function onIndex()
    {
        parent::onIndex();

        $this->sharedViews();
    }

    public function onCreate()
    {
        parent::onCreate();
        $this->sharedViews();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "heading"            => ["required", "string", "max:50"],
            "coupon_code"        => ["required"],
           /* "course_bundle_id"   => ["required"],*/
            "background_color"   => ["required"],
            "button_color"       => ["required"],
            "button_text_color"  => ["required"],
            'activation'         => ['required', 'boolean'],
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
            "heading"            => ["required", "string", "max:50"],
            "coupon_code"        => ["required"],
            /*"course_bundle_id"   => ["required"],*/
            "background_color"   => ["required"],
            "button_color"       => ["required"],
            "button_text_color"  => ["required"],
            'activation'         => ['required', 'boolean'],
        ]);
    }


    
    public function sharedViews()
    {

       
         $total_promo_codes_count = PromoCode::count();
         return view()->share(compact('total_promo_codes_count'));
            
    }







}
