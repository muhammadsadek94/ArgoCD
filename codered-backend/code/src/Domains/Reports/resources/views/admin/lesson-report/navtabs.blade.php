<ul class="nav">

    @permitted([\App\Domains\Reports\Rules\LessonReportPermission::LESSON_REPORT_INDEX])
        <a href="{{ url($admin_base_url . "/lesson-report?video_id=null") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/lesson-report") }}" class="fs-1 text-black px-1" >@lang('reports::lang.lesson_report')</span>
        </a>
    @endpermitted

    @permitted([\App\Domains\Reports\Rules\GlobalKnowledgeReportPermission::KNOWLEDGE_REPORT_INDEX])
    <a href="{{ url($admin_base_url . "/global-knowledge-report") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/global-knowledge-report") }}" class="fs-1 text-black px-1" >@lang('reports::lang.knowledge_report')</span>
    </a>
    @endpermitted

    @permitted([\App\Domains\Reports\Rules\SummaryReportPermission::SUMMARY_REPORT_INDEX])
    <a  href="{{ url($admin_base_url . "/summary-report") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/summary-report") }}" class="fs-1 text-black px-1" >@lang('reports::lang.summary_report')</span>
    </a>
    @endpermitted

</ul>
