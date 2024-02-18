<?php

namespace App\Domains\Blog\Rules;

use App\Foundation\BasicEnum;

class ArticlePermission extends BasicEnum
{
    const MODULE = 'Article';

    const ARTICLE_INDEX = [
        "name"    => "Article list",
        "ability" => "article.index"
    ];

    const ARTICLE_CREATE = [
        "name"    => "Create",
        "ability" => "article.create"
    ];

    const ARTICLE_EDIT = [
        "name"    => "Edit",
        "ability" => "article.edit",
    ];

    const ARTICLE_DELETE = [
        "name"    => "Delete",
        "ability" => "article.delete"
    ];

}
