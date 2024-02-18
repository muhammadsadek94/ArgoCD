<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class ProjectApplicationPermission extends BasicEnum
{
    const MODULE = 'Project Application Management';

    const PROJECT_APPLICATION_INDEX = [
        "name"    => "Project Applications list",
        "ability" => "project_application.index"
    ];

    const PROJECT_APPLICATION_CREATE = [
        "name"    => "Create",
        "ability" => "project_application.create"
    ];

    const PROJECT_APPLICATION_EDIT = [
        "name"    => "Edit",
        "ability" => "project_application.edit",
    ];

    const PROJECT_APPLICATION_DELETE = [
        "name"    => "Delete",
        "ability" => "project_application.delete"
    ];

}
