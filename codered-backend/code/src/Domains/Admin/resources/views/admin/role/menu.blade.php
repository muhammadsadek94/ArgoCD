@permitted([\App\Domains\Admin\Rules\RolesPermission::ROLE_INDEX, \App\Domains\Admin\Rules\RolesPermission::ROLE_CREATE])
<li>
    <a href="javascript: void(0);">
        <i class="fas fa-user-shield"></i>
        <span> @lang('admin::lang.Roles') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">
        @permitted(\App\Domains\Admin\Rules\RolesPermission::ROLE_INDEX)
            <li>
                <a href="{{ url($admin_base_url . "/role") }}">@lang("lang.Index")</a>
            </li>
        @endpermitted
        @permitted(\App\Domains\Admin\Rules\RolesPermission::ROLE_CREATE)
            <li>
                <a href="{{ url($admin_base_url . "/role/create") }}">@lang("lang.create" )</a>
            </li>
        @endpermitted

    </ul>
</li>
@endpermitted