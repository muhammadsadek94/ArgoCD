@permitted([\App\Domains\User\Rules\GoalPermission::GOAL_INDEX, \App\Domains\User\Rules\GoalPermission::GOAL_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-bullseye"></i>
		<span> @lang('user::lang.Goals') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
		@permitted(\App\Domains\User\Rules\GoalPermission::GOAL_INDEX)
		<li>
			<a href="{{ url($admin_base_url . "/goal") }}">@lang("lang.Index")</a>
		</li>
		@endpermitted

		@permitted(\App\Domains\User\Rules\GoalPermission::GOAL_CREATE)
		<li>
			<a href="{{ url($admin_base_url . "/goal/create") }}">@lang("lang.create" )</a>
		</li>
		@endpermitted
	</ul>
</li>
@endpermitted

