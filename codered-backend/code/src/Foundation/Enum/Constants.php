<?php

namespace App\Foundation\Enum;

use App\Foundation\BasicEnum;

class Constants extends BasicEnum {

    const ADMIN_BASE_URL = 'admin';
    const ADMIN_LTR_LAYOUT = 'master';
    const ADMIN_RTL_LAYOUT = 'master-rtl';
    const ADMIN_DEFAULT_VIEW_PATH = self::ADMIN_LTR_LAYOUT;


    CONST DEFAULT_LANGUAGE = 'en';
}
