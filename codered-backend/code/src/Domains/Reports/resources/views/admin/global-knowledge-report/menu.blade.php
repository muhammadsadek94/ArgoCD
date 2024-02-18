@permitted([\App\Domains\Reports\Rules\GlobalKnowledgeReportPermission::KNOWLEDGE_REPORT_INDEX])
<li>
	<a href="{{ url("{$admin_base_url}/global-knowledge-report") }}">
		<i class="fas fa-file-contract"></i>
		<span> @lang('reports::lang.knowledge_report') </span>
	</a>
</li>
@endpermitted