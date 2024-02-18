@permitted([\App\Domains\Bundles\Rules\CourseBundlePermission::COURSE_BUNDLE_INDEX, \App\Domains\Bundles\Rules\CourseBundlePermission::COURSE_BUNDLE_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-book"></i>
		<span> @lang('bundles::lang.course_bundle') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
			@permitted(\App\Domains\Bundles\Rules\CourseBundlePermission::COURSE_BUNDLE_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/course-bundle") }}">@lang("lang.Index")</a>
			</li>
			@endpermitted

			@permitted(\App\Domains\Bundles\Rules\CourseBundlePermission::COURSE_BUNDLE_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/course-bundle/create") }}">@lang("lang.create" )</a>
			</li>
			@endpermitted
	</ul>
</li>
@endpermitted


