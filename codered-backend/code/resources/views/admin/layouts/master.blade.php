<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title', trans('lang.dashboard'))</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{  asset('admin/assets/images/favicon.ico') }}">
        @include('admin.layouts.head')

        @include('admin.layouts.configurations')



    </head>

    <body class="@yield('body_class')">
          <!-- Begin page -->
          <div id="wrapper">
              @include('admin.layouts.topbar')
              @include('admin.layouts.sidebar')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                      @yield('content')
                      @include('admin.layouts.right-sidebar')
                </div> <!-- content -->
                @include('admin.layouts.footer')
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    @include('admin.layouts.footer-script')
    </body>
</html>
