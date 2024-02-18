<?php

namespace App\Domains\Blog\Http\Controllers\Admin;

use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Blog\Rules\ArticlePermission;
use App\Domains\Blog\Models\Article;
use App\Domains\Blog\Models\ArticleCategory;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Http\Controllers\Admin\CoreController;

class ArticleController extends CoreController
{
    use HasAuthorization;

    public $domain = "blog";

    public function __construct(Article $model)
    {
        $this->model = $model;

        $this->pushBreadcrumb(trans('lang.blog'), null, true);
        
        $this->select_columns = [

            [
                'name' => trans("blog::lang.article_name"),
                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("blog::lang.article_category"),
                'type' => ColumnTypes::CALLBACK,
                'key'  => function($row) {
                    return $row->category->name ?? null;
                },
            ],
            [
                'name' => trans("blog::lang.total_views"),
                'type' => ColumnTypes::CALLBACK,
                'key'  => function($row) {
                    return $row->views ?? null;
                },
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
            'index'  => ArticlePermission::ARTICLE_INDEX,
            'create' => ArticlePermission::ARTICLE_CREATE,
            'edit'   => ArticlePermission::ARTICLE_EDIT,
            'show'   => null,
            'delete' => ArticlePermission::ARTICLE_DELETE,
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
            "name"                => ["required", "string", "max:100"],
            "image_id"            => ['required', "exists:uploads,id"],
            "internal_image_id"   => ['required', "exists:uploads,id"],
            "article_category_id" => ["required", "exists:article_categories,id"],
            'content'             => ['required'],
            'activation'          => ['required'],
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
            "name"                => ["required", "string", "max:100"],
            "image_id"            => ['required', "exists:uploads,id"],
            "internal_image_id"   => ['required', "exists:uploads,id"],
            "article_category_id" => ["required", "exists:article_categories,id"],
            'content'             => ['required'],
            'activation'          => ['required'],
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
        $categories_list = ArticleCategory::active()->pluck('name', 'id');
        return view()->share(compact('categories_list'));
        
    }
}
