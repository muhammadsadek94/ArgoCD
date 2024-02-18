@permitted([App\Domains\Partner\Rules\PartnerPermissions::PARTNER_INDEX, App\Domains\Partner\Rules\PartnerPermissions::PARTNER_CREATE])
<li>
    <a href="{{ url($admin_base_url . "/partner") }}"
       class="{{ in_array(request()->path(), ['admin/partner']) ? "active" : "" }}">
        <i class="fe-user"></i>
        <span> @lang('partner::lang.Partners') </span>
{{--        <span class="menu-arrow"></span>--}}
    </a>

{{--    <ul class="nav-second-level nav" aria-expanded="false">--}}
{{--        @permitted(App\Domains\Partner\Rules\PartnerPermissions::PARTNER_INDEX)--}}
{{--        <li>--}}
{{--            <a href="{{ url($admin_base_url . "/partner") }}">@lang("lang.Index")</a>--}}
{{--        </li>--}}
{{--        @endpermitted--}}

{{--        @permitted(App\Domains\Partner\Rules\PartnerPermissions::PARTNER_CREATE)--}}
{{--        <li>--}}
{{--            <a href="{{ url($admin_base_url . "/partner/create") }}">@lang("lang.create" )</a>--}}
{{--        </li>--}}
{{--        @endpermitted--}}
{{--    </ul>--}}

</li>
@endpermitted
