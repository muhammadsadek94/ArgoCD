<ul class="nav">

    @permitted([\App\Domains\User\Rules\UserPermission::USER_INDEX])
        <a href="{{ url($admin_base_url . '/user') }}" id="nav-tab"
            class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . '/user') }}" class="fs-1 text-black px-1">Users</span>
        </a>
    @endpermitted

    @permitted([\App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX])
        <a href="{{ url($admin_base_url . '/user-tag') }}" id="nav-tab"
            class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . '/user-tag') }}" class="fs-1 text-black px-1">User Tags</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_INDEX)
        <a href="{{ url($admin_base_url . '/instructor') }}" id="nav-tab"
            class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . '/instructor') }}" class="fs-1 text-black px-1">Instructors</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\User\Rules\PayoutPermission::PAYOUT_INDEX)
        <a href="{{ url($admin_base_url . '/payout') }}" id="nav-tab"
            class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . '/payout') }}" class="fs-1 text-black px-1">Payout</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\User\Rules\PayoutPermission::PAYOUT_INDEX)
        <a href="{{ url($admin_base_url . '/payout/export-reports') }}" id="nav-tab"
            class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . '/payout/export-reports') }}"
                class="fs-1 text-black px-1">Reports</span>
        </a>
    @endpermitted

    <a href="{{ url($admin_base_url . '/default-image') }}" id="nav-tab"
        class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . '/default-image') }}"
            class="fs-1 text-black px-1">Avatars</span>
    </a>


</ul>
