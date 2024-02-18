@permitted([\App\Domains\Reports\Rules\LessonReportPermission::LESSON_REPORT_INDEX])
<li>
	<a href="{{ url("{$admin_base_url}/lesson-report?video_id=null") }}">
		<i class="fas fa-file-contract"></i>
		<span> @lang('reports::lang.lesson_report') </span>
	</a>
</li>
@endpermitted
