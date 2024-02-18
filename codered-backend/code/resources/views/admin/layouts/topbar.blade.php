<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">

{{--        @include('admin.layouts.quick-search')--}}
{{--        @include('admin.layouts.notifications')--}}

        <li class="dropdown notification-list">
            <a style="color:#323232" class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#"
               role="button" aria-haspopup="false" aria-expanded="false">
                <img src="{{  asset($auth->image->full_url) }}" alt="user-image" class="rounded-circle">
                <span class="pro-user-name ml-1">
                    {{  $auth->name }} <i class="mdi mdi-chevron-down"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">@lang('lang.Welcome') !</h6>
                </div>

                <!-- item-->
                <a href="{{ url("{$admin_base_url}/admin/my-account/update-profile") }}" class="dropdown-item notify-item">
                    <i class="fe-user"></i>
                    <span>@lang('lang.my_account')</span>
                </a>
                <div class="dropdown-divider"></div>
                <!-- item-->
                <form action="{{  route("{$admin_base_url}.logout") }}" method="post">
                    @csrf
                    <button href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>@lang('lang.logout')</span>
                    </button>
                </form>

            </div>
        </li>

            {{-- Uncomment following code to enable right side menu--}}
{{--        <li class="dropdown notification-list">--}}
{{--            <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">--}}
{{--                <i class="fe-settings noti-icon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}

    </ul>
    <!-- LOGO -->
    <div class="logo-box">
        <a href="{{ url(Constants::ADMIN_BASE_URL) }}" class="logo text-center">
            <span class="logo-lg">
                <img src="{{  asset('assets/imgs/logo.png') }}" alt="" height="30">
            </span>
            <span class="logo-sm text-white">
                INT
{{--                <img src="{{  asset('assets/imgs/logo.png') }}" alt="" height="24">--}}
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button style="color:#323232" class="button-menu-mobile waves-effect waves-light">
                <i class="fe-menu"></i>
            </button>
        </li>

        @include('admin.layouts.quick-menu')
{{--        @include('admin.layouts.mega-menu')--}}

    </ul>
</div>
<!-- end Topbar -->
