<ul class="nav">

    @permitted([\App\Domains\Bundles\Rules\CourseBundlePermission::COURSE_BUNDLE_INDEX])
        <a href="{{ url($admin_base_url . "/course-bundle") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/course-bundle") }}" class="fs-1 text-black px-1" >@lang('bundles::lang.course_bundle')</span>
        </a>
    @endpermitted


    @permitted(\App\Domains\Bundles\Rules\PromoCodePermission::PROMO_CODE_INDEX)
    <a  href="{{ url($admin_base_url . "/promo-code") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/promo-code") }}" class="fs-1 text-black px-1" >@lang('bundles::lang.promo_code')</span>
    </a>
    @endpermitted

</ul>
