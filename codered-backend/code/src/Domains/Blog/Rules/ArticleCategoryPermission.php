<?php

namespace App\Domains\Blog\Rules;

use App\Foundation\BasicEnum;

class ArticleCategoryPermission extends BasicEnum
{
    const MODULE = 'Article Categories';

    const ARTICLE_CATEGORY_INDEX = [
        "name"    => "Article categories list",
        "ability" => "article_category.index"
    ];

    const ARTICLE_CATEGORY_CREATE = [
        "name"    => "Create",
        "ability" => "article_category.create"
    ];

    const ARTICLE_CATEGORY_EDIT = [
        "name"    => "Edit",
        "ability" => "article_category.edit",
    ];

    const ARTICLE_CATEGORY_DELETE = [
        "name"    => "Delete",
        "ability" => "article_category.delete"
    ];

}
