<?php

namespace App\Domains\Faq\Http\Controllers\Admin;

use App\Domains\Faq\Rules\FaqPermission;
use App\Domains\Faq\Enum\ActivationTypes;
use App\Domains\Faq\Enum\FaqTypes;
use App\Domains\Faq\Models\Faq;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Foundation\Http\Controllers\Admin\CoreController;



class FaqController extends CoreController
{
    use HasAuthorization;

    public $domain = "faq";

    public function __construct(Faq $model)
    {
        $this->model = $model;

       // $this->pushBreadcrumb(trans('lang.settings'), null, true);
        $this->pushBreadcrumb(trans('lang.pages'), null, true);

        $this->select_columns = [
            [
                'name'         => trans("faq::lang.types"),
                'key'          => 'type',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    FaqTypes::PRICE   => [
                        'text'  => trans('faq::lang.pricing'),
                        'class' => 'badge badge-success',
                    ],
                    FaqTypes::ACCOUNT => [
                        'text'  => trans('faq::lang.account'),
                        'class' => 'badge badge-success',
                    ],
                    FaqTypes::COURSES => [
                        'text'  => trans('faq::lang.courses'),
                        'class' => 'badge badge-success',
                    ],
                    FaqTypes::CERTIFICATES => [
                        'text'  => trans('faq::lang.certificates'),
                        'class' => 'badge badge-success',
                    ],
                ]
            ],

            [
                'name' => trans("faq::lang.question"),
                'key'  => 'question_en',
                'type' => ColumnTypes::STRING,
            ],
//            [
//                'name' => trans("faq::lang.question_ar"),
//                'key'  => 'question_ar',
//                'type' => ColumnTypes::STRING,
//            ],
            [
                'name'         => trans("faq::lang.status"),
                'key'          => 'activation',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    ActivationTypes::ACTIVE   => [
                        'text'  => trans('faq::lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    ActivationTypes::INACTIVE => [
                        'text'  => trans('faq::lang.suspended'),
                        'class' => 'badge badge-danger',
                    ],
                ]
            ],
        ];

        $this->searchColumn = ["question_en"];

        parent::__construct();
    }



    public function onIndex()
    {


        $this->hasPermission(FaqPermission::FAQ_INDEX);
    }

    public function onCreate()
    {
        $this->hasPermission(FaqPermission::FAQ_CREATE);
    }

    public function onStore()
    {
        $this->hasPermission(FaqPermission::FAQ_CREATE);

        $this->validate($this->request, [
            "question_en" => ["required", 'max:255'],
//            "question_ar" => ["required", 'max:255'],
//            "answer_ar"   => ["required"],
            "answer_en"   => ["required"],
            "activation"  => ["required", 'max:1']
        ]);
    }

    public function onEdit()
    {
        $this->hasPermission(FaqPermission::FAQ_EDIT);
    }


    public function onUpdate()
    {
        $this->hasPermission(FaqPermission::FAQ_EDIT);

        $this->validate($this->request, [
            "question_en" => ["required", 'max:255'],
//            "question_ar" => ["required", 'max:255'],
//            "answer_ar"   => ["required"],
            "answer_en"   => ["required"],
            "activation"  => ["required", 'max:1'],
        ]);
    }

    public function onDestroy()
    {
        $this->hasPermission(FaqPermission::FAQ_DELETE);
    }


    public function callbackQuery($query)
    {

        if($this->request->has('typesearch')){
            $query->where('type', $this->request->typesearch);
        }


        return $query;
    }

}
