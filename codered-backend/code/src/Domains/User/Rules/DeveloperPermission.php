<?php

namespace App\Domains\User\Rules;

use App\Foundation\BasicEnum;

class DeveloperPermission extends BasicEnum
{
    const MODULE = 'Developer Options';

    const DEVELOPERS_TELESCOPE = [
        "name"    => "Telescope",
        "ability" => "viewTelescope"
    ];

    const DEVELOPERS_HORIZON = [
        "name"    => "Horizon",
        "ability" => "viewHorizon"
    ];


}
