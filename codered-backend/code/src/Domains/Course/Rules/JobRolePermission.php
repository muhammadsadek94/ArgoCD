<?php

namespace App\Domains\Course\Rules;

use App\Foundation\BasicEnum;

class JobRolePermission extends BasicEnum
{
    const MODULE = 'Job role';

    const JOB_ROLE_INDEX = [
        "name"    => "Job Role list",
        "ability" => "job_role.index"
    ];

    const JOB_ROLE_CREATE = [
        "name"    => "Create",
        "ability" => "Job Role.create"
    ];

    const JOB_ROLE_EDIT = [
        "name"    => "Edit",
        "ability" => "job_role.edit",
    ];

    const JOB_ROLE_DELETE = [
        "name"    => "Delete",
        "ability" => "job_role.delete"
    ];

}
