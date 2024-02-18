@permitted([\App\Domains\Course\Rules\CompetencyPermission::JOB_ROLE_INDEX, \App\Domains\Course\Rules\CompetencyPermission::JOB_ROLE_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-folder-open"></i>
		<span> Competencies </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
		@permitted(\App\Domains\Course\Rules\CompetencyPermission::JOB_ROLE_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/competency") }}">@lang("lang.Index")</a>
			</li>
		@endpermitted

		@permitted(\App\Domains\Course\Rules\CompetencyPermission::JOB_ROLE_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/competency/create") }}">@lang("lang.create" )</a>
			</li>
		@endpermitted
	</ul>
</li>
@endpermitted

