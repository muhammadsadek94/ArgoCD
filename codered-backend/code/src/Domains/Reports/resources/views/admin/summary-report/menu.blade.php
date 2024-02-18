@permitted([\App\Domains\Reports\Rules\SummaryReportPermission::SUMMARY_REPORT_INDEX])
<li>
	<a href="{{ url("{$admin_base_url}/summary-report") }}">
		<i class="fas fa-file-contract"></i>
		<span> @lang('reports::lang.summary_report') </span>
	</a>
</li>
@endpermitted
