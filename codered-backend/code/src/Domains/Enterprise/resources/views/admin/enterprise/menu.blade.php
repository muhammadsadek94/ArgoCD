@permitted([\App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_INDEX,
\App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_CREATE])
<li>
    <a href="javascript: void(0);">
        <i class="fas fa-bullseye"></i>
        <span> @lang('Enterprise') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">

    @permitted( \App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_INDEX)
        <li>
            <a href="{{ url($admin_base_url . '/enterprise') }}">@lang("lang.Index")</a>
        </li>
        @endpermitted

        @permitted( \App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_CREATE)
        <li>
            <a href="{{ url($admin_base_url . '/enterprise/create') }}">@lang("lang.create" )</a>
        </li>
        @endpermitted
    </ul>

</li>
@endpermitted

