<ul class="nav">
    @permitted([\App\Domains\User\Rules\UserPermission::USER_INDEX])
        <a href="{{ url($admin_base_url . "/user") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/user") }}" class="fs-1 text-black px-1" >Users</span>
        </a>
    @endpermitted
    @permitted(\App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_INDEX)
        <a href="{{ url($admin_base_url . "/instructor") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/instructor") }}" class="fs-1 text-black px-1">Instructors</span>
        </a>
    @endpermitted
    @permitted(\App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_INDEX)
        <a  href="{{ url($admin_base_url . "/enterprise") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
            <span span data-link="{{ url($admin_base_url . "/enterprise") }}" class="fs-1 text-black px-1" >Enterprise</span>
        </a>
    @endpermitted
</ul>