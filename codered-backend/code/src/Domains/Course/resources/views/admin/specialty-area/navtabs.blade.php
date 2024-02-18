<ul class="nav">

    @permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX])
    <a href="{{ url($admin_base_url . "/course") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/course") }}" class="fs-1 text-black px-1" >@lang('course::lang.Courses')</span>
    </a>
    @endpermitted

    @permitted([\App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX])
    <a href="{{ url("{$admin_base_url}/reviews") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/reviews") }}" class="fs-1 text-black px-1" >@lang('course::lang.Reviews')</span>
    </a>
    @endpermitted

    @permitted(\App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_INDEX)
    <a href="{{ url($admin_base_url . "/course-tag") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/course-tag") }}" class="fs-1 text-black px-1">@lang("course::lang.Course Tags")</span>
    </a>
    @endpermitted

    @permitted(\App\Domains\Course\Rules\JobRolePermission::JOB_ROLE_INDEX)
    <a href="{{ url($admin_base_url . "/job-role") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/job-role") }}" class="fs-1 text-black px-1"> @lang('course::lang.job-role')</span>
    </a>
    @endpermitted

    @permitted(\App\Domains\Course\Rules\SpecialtyAreaPermission::SPECIALTY_AREA_INDEX)
    <a  href="{{ url($admin_base_url . "/specialty-area") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/specialty-area") }}" class="fs-1 text-black px-1" >@lang('course::lang.specialty_area')</span>
    </a>
    @endpermitted
    @permitted(\App\Domains\Course\Rules\CompetencyPermission::COMPETENCY_INDEX)
    <a  href="{{ url($admin_base_url . "/competency") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/competency") }}" class="fs-1 text-black px-1" >Competencies</span>
    </a>
    @endpermitted
    @permitted(\App\Domains\Course\Rules\KsaPermission::KSA_INDEX)
    <a  href="{{ url($admin_base_url . "/ksa") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/ksa") }}" class="fs-1 text-black px-1" >KSAs</span>
    </a>
    @endpermitted


</ul>
