@permitted([\App\Domains\Admin\Rules\AdminPermission::ADMIN_INDEX, \App\Domains\Admin\Rules\AdminPermission::ADMIN_CREATE, \App\Domains\UserActivity\Rules\UserActivityPermission::USER_ACTIVITY_INDEX])
<li>
    <a href="javascript: void(0);">
        <i class="fas fa-user-secret"></i>
        <span> @lang('admin::lang.Admins') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">
        @permitted(\App\Domains\Admin\Rules\AdminPermission::ADMIN_INDEX)
            <li>
                <a href="{{ url($admin_base_url . "/admin") }}">@lang("lang.Index")</a>
            </li>
        @endpermitted

        @permitted(\App\Domains\Admin\Rules\AdminPermission::ADMIN_CREATE)
            <li>
                <a href="{{ url($admin_base_url . "/admin/create") }}">@lang("lang.create" )</a>
            </li>
        @endpermitted

    </ul>
</li>
@endpermitted
