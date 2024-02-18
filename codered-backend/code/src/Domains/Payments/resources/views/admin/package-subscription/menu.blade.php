@permitted([\App\Domains\Payments\Rules\PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_INDEX, \App\Domains\Payments\Rules\PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_CREATE])
<li>
    <a href="javascript: void(0);">
        <i class="fas fa-bullseye"></i>
        <span> @lang('Package Subscription') </span>
        <span class="menu-arrow"></span>
    </a>
    <ul class="nav-third-level nav" aria-expanded="false">
        @permitted(\App\Domains\Payments\Rules\PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_INDEX)
        <li>
            <a href="{{ url($admin_base_url . "/package-subscription") }}">@lang("lang.Index")</a>
        </li>
        @endpermitted

        @permitted(\App\Domains\Payments\Rules\PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_CREATE)
        <li>
            <a href="{{ url($admin_base_url . "/package-subscription/create") }}">@lang("lang.create" )</a>
        </li>
        @endpermitted
    </ul>
</li>
@endpermitted

