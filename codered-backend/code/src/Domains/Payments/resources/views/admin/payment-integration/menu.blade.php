@permitted([\App\Domains\Payments\Rules\PaymentIntegrationPermission::PAYMENT_INTEGRATION_INDEX, \App\Domains\Payments\Rules\PaymentIntegrationPermission::PAYMENT_INTEGRATION_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-bullseye"></i>
		<span> @lang('Payment Integration') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
		@permitted(\App\Domains\Payments\Rules\PaymentIntegrationPermission::PAYMENT_INTEGRATION_INDEX)
		<li>
			<a href="{{ url($admin_base_url . "/payment-integration") }}">@lang("lang.Index")</a>
		</li>
		@endpermitted

		@permitted(\App\Domains\Payments\Rules\PaymentIntegrationPermission::PAYMENT_INTEGRATION_CREATE)
		<li>
			<a href="{{ url($admin_base_url . "/payment-integration/create") }}">@lang("lang.create" )</a>
		</li>
		@endpermitted
	</ul>
</li>
@endpermitted

