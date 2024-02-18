@permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX, \App\Domains\Course\Rules\CoursePermission::COURSE_CREATE])

<li>
	<a href="{{ url("{$admin_base_url}/course") }}">
		<i class="fas fa-video"></i>
		<span> @lang('course::lang.Courses') </span>
	</a>
</li>



<li>
    <a href="{{ url("{$admin_base_url}/reviews") }}">
        <i class="fas fa-comment"></i>
        <span> @lang('course::lang.Reviews') </span>
    </a>
</li>

@endpermitted
