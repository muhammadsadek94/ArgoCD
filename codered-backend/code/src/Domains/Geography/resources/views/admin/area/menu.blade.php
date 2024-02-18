
@permitted([\App\Domains\Geography\Rules\AreaPermission::AREA_INDEX, \App\Domains\Geography\Rules\AreaPermission::AREA_CREATE])
<li>
    <a href="javascript:void(0)">
        <span> @lang('geography::lang.areas') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">
        @permitted(\App\Domains\Geography\Rules\AreaPermission::AREA_INDEX)
            <li>
                <a href="{{ url($admin_base_url . "/area") }}">@lang("lang.Index")</a>
            </li>
        @endpermitted
        
        @permitted(\App\Domains\Geography\Rules\AreaPermission::AREA_CREATE)
            <li>
                <a href="{{ url($admin_base_url . "/area/create") }}">@lang("lang.create" )</a>
            </li>
        @endpermitted
    </ul>
</li>
@endpermitted

