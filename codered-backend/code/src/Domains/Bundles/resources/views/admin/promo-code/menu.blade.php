@permitted([\App\Domains\Bundles\Rules\PromoCodePermission::PROMO_CODE_INDEX, \App\Domains\Bundles\Rules\PromoCodePermission::PROMO_CODE_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fa fa-tag fa-lg"></i>
		<span> @lang('bundles::lang.promo_code') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
			@permitted(\App\Domains\Bundles\Rules\PromoCodePermission::PROMO_CODE_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/promo-code") }}">@lang("lang.Index")</a>
			</li>
			@endpermitted

			@permitted(\App\Domains\Bundles\Rules\PromoCodePermission::PROMO_CODE_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/promo-code/create") }}">@lang("lang.create" )</a>
			</li>
			@endpermitted
	</ul>
</li>
@endpermitted


