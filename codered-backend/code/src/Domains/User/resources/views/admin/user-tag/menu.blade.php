@permitted([\App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX, \App\Domains\User\Rules\UserTagPermission::USER_TAG_CREATE])
<li>
	<a href="javascript: void(0);">
		<span> @lang('User Tags') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
		@permitted(\App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX)
		<li>
			<a href="{{ url($admin_base_url . "/user-tag") }}">@lang("lang.Index")</a>
		</li>
		@endpermitted

		@permitted(\App\Domains\User\Rules\UserTagPermission::USER_TAG_CREATE)
		<li>
			<a href="{{ url($admin_base_url . "/user-tag/create") }}">@lang("lang.create" )</a>
		</li>
		@endpermitted
	</ul>
</li>
@endpermitted

