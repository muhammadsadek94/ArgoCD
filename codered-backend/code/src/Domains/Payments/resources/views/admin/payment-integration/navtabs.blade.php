<ul class="nav">

    @permitted([\App\Domains\Payments\Rules\PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_INDEX])
        <a href="{{ url($admin_base_url . "/package-subscription") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/package-subscription") }}" class="fs-1 text-black px-1" >Offers</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\Payments\Rules\PaymentIntegrationPermission::PAYMENT_INTEGRATION_INDEX)
    <a href="{{ url($admin_base_url . "/payment-integration") }}" id="nav-tab" class="nav-item top-right-border-radius tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/payment-integration") }}" class="fs-1 text-black px-1" >Payment Integrations</span>
    </a>
    @endpermitted



</ul>
