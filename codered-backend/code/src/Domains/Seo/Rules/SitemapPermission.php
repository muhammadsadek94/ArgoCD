<?php

namespace App\Domains\Seo\Rules;

use App\Foundation\BasicEnum;

class SitemapPermission extends BasicEnum
{
    const MODULE = 'Site map';

    const GOAL_INDEX = [
        "name"    => "Download",
        "ability" => "sitemap.index"
    ];

}
