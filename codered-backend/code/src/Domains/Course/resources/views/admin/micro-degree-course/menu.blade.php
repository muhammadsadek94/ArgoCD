
@permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX, \App\Domains\Course\Rules\CoursePermission::COURSE_CREATE])
<li>
	<a href="{{ url("{$admin_base_url}/micro-degree-course") }}">
		<i class="fas fa-file-video"></i>
		<span> @lang('Micro Degrees') </span>
	</a>
</li>
@endpermitted
