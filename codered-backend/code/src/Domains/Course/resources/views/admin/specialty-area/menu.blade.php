@permitted([\App\Domains\Course\Rules\SpecialtyAreaPermission::SPECIALTY_AREA_INDEX, \App\Domains\Course\Rules\SpecialtyAreaPermission::SPECIALTY_AREA_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-folder-open"></i>
		<span> @lang('course::lang.specialty_area') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
		@permitted(\App\Domains\Course\Rules\SpecialtyAreaPermission::SPECIALTY_AREA_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/specialty-area") }}">@lang("lang.Index")</a>
			</li>
		@endpermitted

		@permitted(\App\Domains\Course\Rules\SpecialtyAreaPermission::SPECIALTY_AREA_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/specialty-area/create") }}">@lang("lang.create" )</a>
			</li>
		@endpermitted
	</ul>
</li>
@endpermitted
