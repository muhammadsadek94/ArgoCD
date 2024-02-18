@permitted([\App\Domains\User\Rules\UserPermission::USER_INDEX, \App\Domains\User\Rules\UserPermission::USER_CREATE,
\App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX, \App\Domains\User\Rules\UserTagPermission::USER_TAG_CREATE])
<li>
    <a href="javascript: void(0);">
        <i class="fe-user"></i>
        <span> @lang('user::lang.Users') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">
        @permitted([\App\Domains\User\Rules\UserPermission::USER_INDEX])
        <li>
            <a href="{{ url($admin_base_url . "/user") }}">@lang("lang.all_users")</a>
        </li>
        @endpermitted
        @permitted([\App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX,
        \App\Domains\User\Rules\UserTagPermission::USER_TAG_CREATE])
        <li>
            @include('user::admin.user-tag.menu')
        </li>
        @endpermitted
        @permitted(\App\Domains\User\Rules\UserPermission::USER_CREATE)
        <li>
        <!-- <a href="{{ url($admin_base_url . "/user/create") }}">@lang("lang.create" )</a> -->
            <a href="{{ url($admin_base_url . "/user/create") }}">@lang("lang.create_a_new_user" )</a>
        </li>
        @endpermitted


    </ul>
</li>
@endpermitted
