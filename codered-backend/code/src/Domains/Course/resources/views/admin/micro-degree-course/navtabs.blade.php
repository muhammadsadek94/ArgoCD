<ul class="nav">

    @permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX])
        <a href="{{ url($admin_base_url . "/micro-degree-course") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/micro-degree-course") }}" class="fs-1 text-black px-1" >@lang(' MicroDegrees')</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\Course\Rules\SpecialtyAreaPermission::SPECIALTY_AREA_INDEX)
        <a  href="{{ url($admin_base_url . "/application-project") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/application-project") }}" class="fs-1 text-black px-1" >@lang('Project Submissions')</span>
        </a>
    @endpermitted



</ul>
