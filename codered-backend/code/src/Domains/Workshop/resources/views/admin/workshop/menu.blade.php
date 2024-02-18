@permitted([App\Domains\Workshop\Rules\WorkshopPermission::WORKSHOP_INDEX, App\Domains\Workshop\Rules\WorkshopPermission::WORKSHOP_CREATE])
<li>
    <a href="{{ url($admin_base_url . "/workshop") }}"
       class="{{ in_array(request()->path(), ['admin/workshop']) ? "active" : "" }}">
        <i class="far fa-calendar-check"></i>
        <span> @lang('Workshops') </span>
{{--        <span class="menu-arrow"></span>--}}
    </a>

{{--    <ul class="nav-second-level nav" aria-expanded="false">--}}
{{--        @permitted(App\Domains\Workshop\Rules\WorkshopPermission::WORKSHOP_INDEX)--}}
{{--        <li>--}}
{{--            <a href="{{ url($admin_base_url . "/workshop") }}">@lang("lang.Index")</a>--}}
{{--        </li>--}}
{{--        @endpermitted--}}

{{--        @permitted(App\Domains\Workshop\Rules\WorkshopPermission::WORKSHOP_CREATE)--}}
{{--        <li>--}}
{{--            <a href="{{ url($admin_base_url . "/workshop/create") }}">@lang("lang.create" )</a>--}}
{{--        </li>--}}
{{--        @endpermitted--}}
{{--    </ul>--}}

</li>
@endpermitted
