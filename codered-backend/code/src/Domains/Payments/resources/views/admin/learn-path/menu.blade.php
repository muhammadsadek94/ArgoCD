@permitted([\App\Domains\Payments\Rules\LearnPathPermission::LEARN_PATH_INDEX, \App\Domains\Payments\Rules\LearnPathPermission::LEARN_PATH_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-book"></i>
		<span> @lang('payments::lang.learn-path') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
			@permitted(\App\Domains\Payments\Rules\LearnPathPermission::LEARN_PATH_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/learn-path") }}">@lang("lang.Index")</a>
			</li>
			@endpermitted

			@permitted(\App\Domains\Payments\Rules\LearnPathPermission::LEARN_PATH_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/learn-path/create") }}">@lang("lang.create" )</a>
			</li>
			@endpermitted
	</ul>
</li>
@endpermitted


