@extends("admin.layouts.main")

@section('title', 'Logged in !')

@section('content')

    <div class="container-fluid px-5">
        <div class="row mt-3 px-3">
            <div class="col-lg-6 font-bold">
                <h3 class="text-black my-2 font-semibold mb-3">Welcome {{ auth()->guard('admin')->user()->name }}</h3>
            </div>
        </div>
        <div class="row quick-navigation px-3">
            <div class="col-lg-6 pr-3">
                <div class="h-300px bg-white mb-3 radius-5 shadow-xs">
                    <div class="col-12 d-flex align-items-center py-2 text-white bg-black px-3 radius-top-5">
                        <i class="fa fa-users mr-1" aria-hidden="true"></i>
                        <span>
                            Users Management
                        </span>
                    </div>
                    <div class="col-12 d-flex flex-column px-3">
                        <div class="my-1 mt-2 pt-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/user") }}">View all users</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/user-tag") }}">View all user tags</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/user/create") }}">Create a new user</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/user-tag/create") }}">Create a new tag</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/instructor") }}">View all Instructors</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/instructor/create") }}">Create a new instructor</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/proctor-user/create") }}">Create a proctor user</a>
                        </div>
                    </div>
                    </p>
                </div>

                <div class="h-300px bg-white mb-3 radius-5 shadow-xs">
                    <div class="col-12 d-flex align-items-center py-2 text-white bg-black px-3 radius-top-5">
                        <i class="fas fa-dollar-sign mr-1" aria-hidden="true"></i>
                        <span>
                            Manage Offers
                        </span>
                    </div>
                    <div class="col-12 d-flex flex-column px-3">
                        <div class="my-1 mt-2 pt-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/package-subscription") }}">View all offers</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/package-subscription/create") }}">Create a new Offer</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/payment-integration") }}">View all payment integrations</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/payment-integration/create") }}">Create a new payment integration</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/subscription-cancellation") }}">View cancellation requests</a>
                        </div>
                    </div>
                    </p>
                </div>

                <div class="h-300px bg-white mb-3 radius-5 shadow-xs">
                    <div class="col-12 d-flex align-items-center py-2 text-white bg-black px-3 radius-top-5">
                        <i class="fas fa-book-open mr-1" aria-hidden="true"></i>
                        <span>
                            Manage Courses
                        </span>
                    </div>
                    <div class="col-12 d-flex flex-column px-3">
                        <div class="my-1 mt-2 pt-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/course") }}">View all courses</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/course/create") }}">Create a new course</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/course-tag") }}">View all course tags</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/course-tag/create") }}">Create course tags</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/micro-degree-course") }}">View all Microdegrees</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/micro-degree-course/create") }}">Create a Microdegree</a>
                        </div>
                    </div>
                    </p>
                </div>

                {{-- <div class="h-300px bg-white mb-3 radius-5 shadow-xs">
                    <div class="col-12 d-flex align-items-center py-2 text-white bg-black px-3 radius-top-5">
                        <i class="fas fa-file-archive mr-1" aria-hidden="true"></i>
                        <span>
                            Manage Bundles
                        </span>
                    </div>
                    <div class="col-12 d-flex flex-column px-3">
                        <div class="my-1 mt-2 pt-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/course-bundle") }}">View all course bundles</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/course-bundle/create") }}">Create a new bundle</a>
                        </div>
                    </div>
                    </p>
                </div> --}}
            </div>

            <div class="col-lg-6 pr-3">
                <div class="h-300px bg-white mb-3 radius-5 shadow-xs">
                    <div class="col-12 d-flex align-items-center py-2 text-white bg-black px-3 radius-top-5">
                        <i class="fas fa-user-secret mr-1" aria-hidden="true"></i>
                        <span>
                            Manage Admins
                        </span>
                    </div>
                    <div class="col-12 d-flex flex-column px-3">
                        <div class="my-1 mt-2 pt-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/admin") }}">View all admins</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/admin/create") }}">Create a new admin user</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/role") }}">View all admin roles</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/role/create") }}">Create a new admin role</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/user-activity") }}">View User activity</a>
                        </div>
                    </div>
                    </p>
                </div>

                <div class="h-300px bg-white mb-3 radius-5 shadow-xs">
                    <div class="col-12 d-flex align-items-center py-2 text-white bg-black px-3 radius-top-5">
                        <i class="fas fa-ticket-alt mr-1" aria-hidden="true"></i>
                        <span>
                            Manage Vouchers
                        </span>
                    </div>
                    <div class="col-12 d-flex flex-column px-3">
                        <div class="my-1 mt-2 pt-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/voucher") }}">View all vouchers</a>
                        </div>
                        <div class="my-1">
                            <a class="link-color text-decoration-none" href="{{ url("$admin_base_url/voucher/create") }}">Create new vouchers</a>
                        </div>
                    </div>
                    </p>
                </div>


            </div>
        </div>

        {{--
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-success p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/user") }}">
                                <h3 class="text-white m-b-5 ">
                                    Users Management
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/user") }}">
                            <i style="color:white; font-size: 100px" class="fa fa-users"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-danger p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/admin") }}">
                                <h3 class="text-white m-b-5 ">
                                    Admins Management
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/admin") }}">
                            <i style="color:white; font-size: 100px" class="fa fa-user-secret"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-warning p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/instructor") }}">
                                <h3 class="text-white m-b-5 ">
                                    Instructors Management
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/instructor") }}">
                            <i style="color:white; font-size: 100px" class="fa fa-user-check"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-info p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/faq") }}">
                                <h3 class="text-white m-b-5 ">
                                    Manage Faq
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/faq") }}">
                            <i style="color:white; font-size: 100px" class="fa fa-question-circle"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-primary p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/package-subscription") }}">
                                <h3 class="text-white m-b-5 ">
                                    Package Subscription
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/package-subscription") }}">
                            <i style="color:white; font-size: 100px" class="fas fa-credit-card"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-dark p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/payment-integration") }}">
                                <h3 class="text-white m-b-5 ">
                                    Payment Integrations
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/payment-integration") }}">
                            <img style="padding: 1.05rem 0;"
                                 src="https://qn03e1pj8r21z48mq2u9ctcv-wpengine.netdna-ssl.com/wp-content/themes/samcart/assets/images/logo.svg"
                                 alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-success p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/voucher") }}">
                                <h3 class="text-white m-b-5 ">
                                    Vouchers
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/voucher") }}">
                            <i style="color:white; font-size: 100px" class="fas fa-ticket-alt"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-success p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/course") }}">
                                <h3 class="text-white m-b-5 ">
                                    Manage courses
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/course") }}">
                            <i style="color:white; font-size: 100px" class="fas fa-video"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-success p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/micro-degree-course") }}">
                                <h3 class="text-white m-b-5 ">
                                    Manage Microdegrees
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/micro-degree-course") }}">
                            <i style="color:white; font-size: 100px" class="fas fa-file-video"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-success p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/course-bundle") }}">
                                <h3 class="text-white m-b-5 ">
                                    Manage Bundles
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/course-bundle") }}">
                            <i style="color:white; font-size: 100px" class="fas fa-book"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="widget-profile-one">
                    <div class="card-box  m-b-0 b-0 bg-success p-lg text-center">
                        <div class="m-b-30">
                            <a href="{{ url("$admin_base_url/promo-code") }}">
                                <h3 class="text-white m-b-5 ">
                                    Promo Code
                                </h3>
                            </a>
                        </div>
                        <a href="{{ url("$admin_base_url/promo-code") }}">
                            <i style="color:white; font-size: 100px" class="fas fa-book"
                               aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        --}}
    </div>
@endsection


