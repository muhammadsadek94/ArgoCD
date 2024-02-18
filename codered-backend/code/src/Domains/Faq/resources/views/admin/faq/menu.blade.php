@permitted([\App\Domains\Faq\Rules\FaqPermission::FAQ_INDEX, \App\Domains\Faq\Rules\FaqPermission::FAQ_CREATE])
    <li>
        <a href="{{ url($admin_base_url . "/faq") }}">
            <i class="fas fa-question"></i>
            <span> @lang('faq::lang.faq') </span>
{{--            <span class="menu-arrow"></span>--}}
        </a>
{{--        <ul class="nav-third-level nav" aria-expanded="false">--}}
{{--            @permitted(\App\Domains\Faq\Rules\FaqPermission::FAQ_INDEX)--}}
{{--                <li>--}}
{{--                    <a href="{{ url($admin_base_url . "/faq") }}">@lang("lang.Index")</a>--}}
{{--                </li>--}}
{{--            @endpermitted--}}

{{--            @permitted(\App\Domains\Faq\Rules\FaqPermission::FAQ_CREATE)--}}
{{--                <li>--}}
{{--                    <a href="{{ url($admin_base_url . "/faq/create") }}">@lang("lang.create" )</a>--}}
{{--                </li>--}}
{{--            @endpermitted--}}
{{--        </ul>--}}
    </li>
@endpermitted

