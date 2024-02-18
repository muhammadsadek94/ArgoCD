@permitted([\App\Domains\Payments\Rules\SubscriptionCancellationPermission::SUBSCRIPTION_CANCELLATION_INDEX])
<li>
	<a href="{{ url($admin_base_url . "/subscription-cancellation") }}"
       class="{{ in_array(request()->path(), ['admin/subscription-cancellation']) ? "active" : "" }}">
		<i class="fas fa-bullseye"></i>
		<span> @lang('Cancellation Requests') </span>
	</a>
</li>
@endpermitted

