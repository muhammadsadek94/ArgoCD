@permitted([\App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_INDEX, \App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-tag"></i>
		<span> @lang('course::lang.Course Tags') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
		@permitted(\App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_INDEX)
		<li>
			<a href="{{ url($admin_base_url . "/course-tag") }}">@lang("lang.Index")</a>
		</li>
		@endpermitted
		
		@permitted(\App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_CREATE)
		<li>
			<a href="{{ url($admin_base_url . "/course-tag/create") }}">@lang("lang.create" )</a>
		</li>
		@endpermitted
	</ul>
</li>
@endpermitted

