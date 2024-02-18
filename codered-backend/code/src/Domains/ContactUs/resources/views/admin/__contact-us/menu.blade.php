@permitted([\App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_INDEX, \App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_CREATE])
<li>
    <a href="javascript: void(0);">
        <i class="fe-user"></i>
        <span> @lang('Instructors') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">
        @permitted(\App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_INDEX)
            <li>
                <a href="{{ url($admin_base_url . "/instructor") }}">@lang("lang.all_users")</a>
            </li>
        @endpermitted
        @permitted(\App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_CREATE)
            <li>
                <a href="{{ url($admin_base_url . "/instructor/create") }}">Create New Instructor</a>
            </li>
        @endpermitted
        @include('user::admin.payout.menu')

    </ul>
</li>
@endpermitted

