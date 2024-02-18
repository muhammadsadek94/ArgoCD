<ul class="nav">

    @permitted(\App\Domains\Admin\Rules\AdminPermission::ADMIN_INDEX)
        <a href="{{ url($admin_base_url . "/admin") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/admin") }}" class="fs-1 text-black px-1" >Admins</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\Admin\Rules\RolesPermission::ROLE_INDEX)
        <a href="{{ url($admin_base_url . "/role") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/role") }}" class="fs-1 text-black px-1">Role Management</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\Course\Rules\ProctorPermission::PROCTOR_INDEX)
    <a href="{{ url($admin_base_url . "/proctor-user") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/proctor-user") }}" class="fs-1 text-black px-1">Proctor Users</span>
    </a>
    @endpermitted

    @permitted([\App\Domains\UserActivity\Rules\UserActivityPermission::USER_ACTIVITY_INDEX])
        <a  href="{{ url($admin_base_url . "/user-activity") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
            <span span data-link="{{ url($admin_base_url . "/user-activity") }}" class="fs-1 text-black px-1" >Activity Log</span>
        </a>
    @endpermitted

</ul>
