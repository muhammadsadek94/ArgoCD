<?php

namespace App\Domains\Blog\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Blog\Rules\QuotePermission;
use App\Domains\Blog\Models\Quote;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Http\Controllers\Admin\CoreController;

class QuoteController extends CoreController
{
    use HasAuthorization;

    public $domain = "blog";

    public function __construct(Quote $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.blog'), null, true);
        
        $this->select_columns = [
            [
                'name' => trans("blog::lang.author_image"),
                'key'  => 'author_image',
                'type' => ColumnTypes::IMAGE,
            ],

            [
                'name' => trans("blog::lang.author_name"),
                'key'  => 'author_name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("blog::lang.author_position"),
                'key'  => 'author_position',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("blog::lang.quote"),
                'key'  => 'quote',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name'         => trans("blog::lang.status"),
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
        $this->searchColumn = ["author_name","author_position","quote"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => QuotePermission::QUOTE_INDEX,
            'create' => QuotePermission::QUOTE_CREATE,
            'edit'   => QuotePermission::QUOTE_EDIT,
            'show'   => null,
            'delete' => QuotePermission::QUOTE_DELETE,
        ];
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
            "author_name"       => ["required", "string", "max:100"],
            "author_image_id"   => ['required', "exists:uploads,id"],
            "author_position"   => ["required", "string", "max:100"],
            'quote'             => ['required'],
            'activation'        => ['required'],
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
            "author_name"       => ["required", "string", "max:100"],
            "author_image_id"   => ['required', "exists:uploads,id"],
            "author_position"   => ["required", "string", "max:100"],
            'quote'             => ['required'],
            'activation'        => ['required'],
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
}
