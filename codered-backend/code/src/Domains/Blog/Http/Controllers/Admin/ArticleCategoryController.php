<?php

namespace App\Domains\Blog\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Blog\Rules\ArticleCategoryPermission;
use App\Domains\Blog\Models\ArticleCategory;
use App\Foundation\Http\Controllers\Admin\CoreController;

class ArticleCategoryController extends CoreController
{
    
    use HasAuthorization;

    public $domain = "blog";

    public function __construct(ArticleCategory $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.blog'), null, true);
        
        $this->select_columns = [
            [
                'name' => trans("blog::lang.category_name"),
                'key'  => 'name',
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
        $this->searchColumn = ["name"];
        parent::__construct();

        $this->permitted_actions = [
            'index'  => ArticleCategoryPermission::ARTICLE_CATEGORY_INDEX,
            'create' => ArticleCategoryPermission::ARTICLE_CATEGORY_CREATE,
            'edit'   => ArticleCategoryPermission::ARTICLE_CATEGORY_EDIT,
            'show'   => null,
            'delete' => ArticleCategoryPermission::ARTICLE_CATEGORY_DELETE,
        ];
    }

    public function onCreate()
    {
        parent::onCreate();
        //$this->sharedViews();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "name"        => ["required", "string", "max:50"],
            'activation'  => ['required', 'boolean'],
        ]);
    }

    public function onEdit()
    {
        parent::onEdit();
        //$this->sharedViews();
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "name"        => ["required", "string", "max:50"],
            'activation'  => ['required', 'boolean'],
        ]);
    }

    

    public function sharedViews()
    {
        

        
    }







}
