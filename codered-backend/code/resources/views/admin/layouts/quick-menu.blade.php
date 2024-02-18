<li class="dropdown d-none d-lg-block">
{{--    <a style="color:#323232" class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button"--}}
{{--       aria-haspopup="false" aria-expanded="false">--}}
{{--        Create New--}}
{{--        <i class="mdi mdi-chevron-down"></i>--}}
{{--    </a>--}}
    <div class="dropdown-menu">
        <!-- item-->
        <a href="{{ url($admin_base_url . "/user/create") }}" class="dropdown-item">
            <i class="fe-user mr-1"></i>
            <span>Create Users</span>
        </a>
        <!-- item-->


        <!-- item-->
{{--        <a href="javascript:void(0);" class="dropdown-item">--}}
{{--            <i class="fe-briefcase mr-1"></i>--}}
{{--            <span>New Projects</span>--}}
{{--        </a>--}}



{{--        <!-- item-->--}}
{{--        <a href="javascript:void(0);" class="dropdown-item">--}}
{{--            <i class="fe-bar-chart-line- mr-1"></i>--}}
{{--            <span>Revenue Report</span>--}}
{{--        </a>--}}

{{--        <!-- item-->--}}
{{--        <a href="javascript:void(0);" class="dropdown-item">--}}
{{--            <i class="fe-settings mr-1"></i>--}}
{{--            <span>Settings</span>--}}
{{--        </a>--}}

{{--        <div class="dropdown-divider"></div>--}}

{{--        <!-- item-->--}}
{{--        <a href="javascript:void(0);" class="dropdown-item">--}}
{{--            <i class="fe-headphones mr-1"></i>--}}
{{--            <span>Help & Support</span>--}}
{{--        </a>--}}

        @stack('quick_menu')

    </div>
</li>
