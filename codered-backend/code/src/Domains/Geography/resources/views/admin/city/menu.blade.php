@permitted([\App\Domains\Geography\Rules\CityPermission::CITY_INDEX, \App\Domains\Geography\Rules\CityPermission::CITY_CREATE])
<li>
    <a href="javascript:void(0)">
        <span> @lang('geography::lang.cities') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">
        @permitted(\App\Domains\Geography\Rules\CityPermission::CITY_INDEX)
            <li>
                <a href="{{ url($admin_base_url . "/city") }}">@lang("lang.Index")</a>
            </li>
        @endpermitted
        
        @permitted(\App\Domains\Geography\Rules\CityPermission::CITY_CREATE)
            <li>
                <a href="{{ url($admin_base_url . "/city/create") }}">@lang("lang.create" )</a>
            </li>
        @endpermitted
    </ul>
</li>
@endpermitted

