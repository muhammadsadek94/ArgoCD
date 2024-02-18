
@permitted([\App\Domains\Course\Rules\CourseCertificationPermission::COURSE_INDEX, \App\Domains\Course\Rules\CourseCertificationPermission::COURSE_CREATE])
<li>
	<a href="{{ url("{$admin_base_url}/course-certification-course") }}">
		<i class="fas fa-certificate"></i>
		<span> Certification </span>
	</a>
</li>
@endpermitted
