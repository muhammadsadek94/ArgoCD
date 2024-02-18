@permitted([\App\Domains\Geography\Rules\CountryPermission::COUNTRY_INDEX, \App\Domains\Geography\Rules\CountryPermission::COUNTRY_CREATE])
    <li>
        <a href="javascript:void(0)">
            <span> @lang('geography::lang.Countries') </span>
            <span class="menu-arrow"></span>
        </a>
        <ul class="nav-third-level nav" aria-expanded="false">
            @permitted(\App\Domains\Geography\Rules\CountryPermission::COUNTRY_INDEX)
                <li>
                    <a href="{{ url($admin_base_url . "/country") }}">@lang("lang.Index")</a>
                </li>
            @endpermitted
            
            @permitted(\App\Domains\Geography\Rules\CountryPermission::COUNTRY_CREATE)
                <li>
                    <a href="{{ url($admin_base_url . "/country/create") }}">@lang("lang.create" )</a>
                </li>
            @endpermitted
        </ul>
    </li>
@endpermitted


