<ul class="nav">


    @permitted(\App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_INDEX)
    <a  href="{{ url($admin_base_url . "/enterprise") }}" id="nav-tab" class="nav-item top-left-border-radius tabs-border text-decoration-none bg-white px-4 py-2">
        <span span data-link="{{ url($admin_base_url . "/enterprise") }}" class="fs-1 text-black px-1" >Enterprise Users</span>
    </a>
    @endpermitted

    @permitted([\App\Domains\User\Rules\UserPermission::USER_INDEX])
    <a  href="{{ url($admin_base_url . "/enterprise-learn-path") }}" id="nav-tab" class="nav-item tabs-border  text-decoration-none bg-white px-4 py-2">
        <span span data-link="{{ url($admin_base_url . "/enterprise-learn-path") }}" class="fs-1 text-black px-1" >Learning Paths</span>
    </a>

    @endpermitted


    @permitted(App\Domains\Partner\Rules\PartnerPermissions::PARTNER_INDEX)
    <a href="{{ url($admin_base_url . "/partner") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/partner") }}" class="fs-1 text-black px-1"> API Integrations </span>
    </a>
    @endpermitted



    @permitted(\App\Domains\Reports\Rules\GlobalKnowledgeReportPermission::KNOWLEDGE_REPORT_INDEX)
    <a href="{{ url($admin_base_url . "/global-knowledge-report") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/global-knowledge-report") }}" class="fs-1 text-black px-1" >Global Knowledge Report</span>
    </a>
    @endpermitted

</ul>
