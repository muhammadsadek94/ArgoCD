<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

{{--                <li class="menu-title">@lang('lang.Navigation')</li>--}}

                <li>
                    <a href="{{ url("{$admin_base_url}/dashboard") }}"
                       class="{{ in_array(request()->path(), ['admin/dashboard']) ? "active" : "" }}">
                        <i class="fe-airplay"></i>
                        {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                        <span> @lang('lang.dashboard') </span>
                    </a>
{{--                    <ul class="nav-second-level" aria-expanded="false">--}}
{{--                        <li>--}}
{{--                            <a href="{{ url("{$admin_base_url}/dashboard") }}">@lang('lang.dashboard')</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
                </li>




{{--                <li>--}}
{{--                    <a href="javascript: void(0);">--}}
{{--                        <i class="fe-user"></i>--}}
{{--                        <span>CMS</span>--}}
{{--                        <span class="menu-arrow"></span>--}}
{{--                    </a>--}}
{{--                    <ul class="nav-second-level  nav" aria-expanded="false">--}}
{{--                        <li>--}}
{{--                            <a href="{{ url($admin_base_url . '/slider') }}">Manage Sliders</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ url($admin_base_url . '/brand') }}">Manage Brands</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}





                @permitted([\App\Domains\User\Rules\UserPermission::USER_INDEX,
                \App\Domains\User\Rules\UserPermission::USER_CREATE,
                \App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_INDEX,
                \App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_CREATE,
                \App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX,
                \App\Domains\User\Rules\UserTagPermission::USER_TAG_CREATE,
                \App\Domains\User\Rules\PayoutPermission::PAYOUT_INDEX,
                \App\Domains\User\Rules\PayoutPermission::PAYOUT_CREATE,
                \App\Domains\User\Rules\PayoutPermission::PAYOUT_EDIT,
                \App\Domains\User\Rules\PayoutPermission::PAYOUT_APPROVE,
                \App\Domains\User\Rules\PayoutPermission::PAYOUT_DISAPPROVE,
                \App\Domains\User\Rules\PayoutPermission::PAYOUT_PAID,
                ])
					<li>
						<a href="{{ url($admin_base_url . "/user") }}"
                            class="{{ in_array(request()->path(), ['admin/user', 'admin/user-tag', 'admin/instructor', 'admin/payout'   ]) ? "active" : "" }}">
							<i class="fe-user"></i>
							<span> @lang('lang.users_management') </span>
{{--							<span class="menu-arrow"></span>--}}
						</a>
{{--						<ul class="nav-second-level nav">--}}
{{--							@include('user::admin.user.menu')--}}
{{--							@include('user::admin.instructor.menu')--}}

{{--                            @permitted([--}}
{{--                            \App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_INDEX,--}}
{{--                            \App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_CREATE,--}}
{{--                            ])--}}
{{--                            <li>--}}
{{--                                <a href="javascript: void(0);">--}}
{{--                                    <i class="fas fa-file-archive"></i>--}}
{{--                                    <span> @lang('enterprise::lang.enterprise') </span>--}}
{{--                                    <span class="menu-arrow"></span>--}}
{{--                                </a>--}}
{{--                                <ul class="nav-second-level nav" aria-expanded="false">--}}

{{--                                    @include('enterprise::admin.enterprise.menu')--}}
{{--                                </ul>--}}
{{--                                <ul class="nav-second-level nav" aria-expanded="false">--}}

{{--                                    @include('enterprise::admin.enterprise-learn-path.menu')--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            @endpermitted--}}
{{--						</ul>--}}

					</li>
				@endpermitted

                @permitted([
{{--                \App\Domains\User\Rules\UserPermission::USER_INDEX,--}}
{{--                \App\Domains\User\Rules\UserPermission::USER_CREATE,--}}
{{--                \App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_INDEX,--}}
{{--                \App\Domains\User\Rules\InstructorPermission::INSTRUCTOR_CREATE,--}}
{{--                \App\Domains\User\Rules\UserTagPermission::USER_TAG_INDEX,--}}
{{--                \App\Domains\User\Rules\UserTagPermission::USER_TAG_CREATE,--}}
                \App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_INDEX,
                \App\Domains\Enterprise\Rules\EnterprisePermission::ENTERPRISE_CREATE
                ])
                <li>
                    <a href="{{ url($admin_base_url . "/enterprise") }}"
                       class="{{ in_array(request()->path(), ['admin/global-knowledge-report', 'admin/partner', 'admin/enterprise', 'admin/enterprise-learn-path']) ? "active" : "" }}">
                        <i class="fe-user"></i>
                        <span> @lang('Enterprise Management') </span>
                    </a>

                </li>
                @endpermitted



{{--                @permitted([\App\Domains\Faq\Rules\FaqPermission::FAQ_INDEX,--}}
{{--                \App\Domains\Faq\Rules\FaqPermission::FAQ_CREATE,--}}
{{--                \App\Domains\User\Rules\GoalPermission::GOAL_INDEX, \App\Domains\User\Rules\GoalPermission::GOAL_CREATE,--}}
{{--                \App\Domains\Course\Rules\CourseCategoryPermission::COURSE_CATEGORY_INDEX,--}}
{{--                \App\Domains\Course\Rules\CourseCategoryPermission::COURSE_CATEGORY_CREATE,--}}
{{--                \App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_INDEX,--}}
{{--                \App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_CREATE--}}
{{--                ])--}}
{{--                <li>--}}
{{--                    <a href="javascript: void(0);">--}}
{{--                        <i class="fas fa-cog"></i>--}}
{{--                        <span> @lang('lang.pages') </span>--}}
{{--                        <span class="menu-arrow"></span>--}}
{{--                    </a>--}}




{{--                </li>--}}
{{--                @endpermitted--}}

                @permitted([\App\Domains\Payments\Rules\PaymentIntegrationPermission::PAYMENT_INTEGRATION_INDEX,
                \App\Domains\Payments\Rules\LearnPathPermission::LEARN_PATH_CREATE,
                \App\Domains\Payments\Rules\LearnPathPermission::LEARN_PATH_INDEX,
                 \App\Domains\Payments\Rules\PaymentIntegrationPermission::PAYMENT_INTEGRATION_CREATE,
                \App\Domains\Payments\Rules\PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_INDEX, \App\Domains\Payments\Rules\PackageSubscriptionPermission::PACKAGE_SUBSCRIPTION_CREATE,
                \App\Domains\Payments\Rules\SubscriptionCancellationPermission::SUBSCRIPTION_CANCELLATION_INDEX])

                    <li>
                        <a href="{{ url($admin_base_url . "/package-subscription") }}"
                           class="{{ in_array(request()->path(), ['admin/package-subscription', 'admin/payment-integration']) ? "active" : "" }}">
                            <i class="fas fa-dollar-sign"></i>
                            <span> @lang('Offers') </span>
{{--                            <span class="menu-arrow"></span>--}}
                        </a>
{{--                        <ul class="nav-second-level nav" aria-expanded="false">--}}
{{--                            @include('payments::admin.package-subscription.menu')--}}
{{--                            @include('payments::admin.payment-integration.menu')--}}
{{--                            @include('payments::admin.learn-path.menu')--}}
{{--                            @include('payments::admin.subscription-cancellation.menu')--}}
{{--                        </ul>--}}
                    </li>
                @endpermitted


                @permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX,
                \App\Domains\Course\Rules\CoursePermission::COURSE_CREATE,
                \App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_INDEX,
                \App\Domains\Course\Rules\CourseTagPermission::COURSE_TAG_CREATE
                ])
                <li>
                    <a href="{{ url($admin_base_url . "/course") }}"
                       class="{{ in_array(request()->path(), ['admin/course', 'admin/reviews', 'admin/course-tag', 'admin/job-role',
                        'admin/specialty-area']) ? "active" : "" }}">
                        <i class="fas fa-book-open"></i>
                        <span> @lang('Courses') </span>
{{--                        <span class="menu-arrow"></span>--}}
                    </a>
{{--                    <ul class="nav-second-level nav" aria-expanded="false">--}}
{{--                        @include('course::admin.course.menu')--}}
{{--                        @include('course::admin.course-tag.menu')--}}
{{--                        @include('course::admin.job-role.menu')--}}
{{--                        @include('course::admin.specialty-area.menu')--}}
{{--                        @include('course::admin.micro-degree-course.menu')--}}
{{--                        @include('course::admin.course-certification-course.menu')--}}
{{--                        @include('course::admin.proctor-user.menu')--}}
{{--                    </ul>--}}
                </li>
                @endpermitted

                @permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX])
                <li>
                    <a href="{{ url($admin_base_url . "/learn-path") }}"
                       class="{{ in_array(request()->path(), ['admin/learn-path']) ? "active" : "" }}">
                        <i class="fas fa-book"></i>
                        <span> @lang('payments::lang.learn-path') </span>
                    </a>
                </li>
                @endpermitted

                @permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX])
                <li>
                    <a href="{{ url($admin_base_url . "/micro-degree-course") }}"
                       class="{{ in_array(request()->path(), ['admin/micro-degree-course', 'admin/application-project']) ? "active" : "" }}">
                        <i class="fas fa-file-video"></i>
                        <span> @lang('MicroDegrees') </span>
                    </a>
                </li>
                @endpermitted

                @permitted([\App\Domains\Course\Rules\CoursePermission::COURSE_INDEX])
                <li>
                    <a href="{{ url($admin_base_url . "/course-certification-course") }}"
                       class="{{ in_array(request()->path(), ['admin/course-certification-course']) ? "active" : "" }}">
                        <i class="fas fa-certificate"></i>
                        <span> @lang('Certifications') </span>
                    </a>
                </li>
                @endpermitted

                @permitted([\App\Domains\Challenge\Rules\ChallengePermission::CHALLENGE_INDEX])
                <li>
                    <a href="{{ url($admin_base_url . "/challenge") }}"
                       class="{{ in_array(request()->path(), ['admin/challenge']) ? "active" : "" }}">
                        <i class="fas fa-flag"></i>
                        <span> @lang('Competitions') </span>
                    </a>
                </li>
                @endpermitted






                @permitted([
                \App\Domains\Bundles\Rules\CourseBundlePermission::COURSE_BUNDLE_INDEX,
                \App\Domains\Bundles\Rules\CourseBundlePermission::COURSE_BUNDLE_CREATE,
                \App\Domains\Bundles\Rules\PromoCodePermission::PROMO_CODE_INDEX,
                \App\Domains\Bundles\Rules\PromoCodePermission::PROMO_CODE_CREATE,
                ])
{{--                <li>--}}
{{--                    <a href="{{ url($admin_base_url . "/course-bundle") }}"--}}
{{--                       class="{{ in_array(request()->path(), ['admin/course-bundle', 'admin/promo-code']) ? "active" : "" }}">--}}
{{--                        <i class="fas fa-file-archive"></i>--}}
{{--                        <span> @lang('lang.bundle') </span>--}}
{{--                        <span class="menu-arrow"></span>--}}
{{--                    </a>--}}
{{--                    <ul class="nav-second-level nav" aria-expanded="false">--}}

{{--                        @include('bundles::admin.course-bundle.menu')--}}
{{--                        @include('bundles::admin.promo-code.menu')--}}
{{--                    </ul>--}}
{{--                </li>--}}
                @endpermitted



{{--                @permitted([\App\Domains\Course\Rules\ProjectApplicationPermission::PROJECT_APPLICATION_INDEX])--}}
{{--                @include('course::admin.project-application.menu')--}}
{{--                @endpermitted--}}



                @permitted([\App\Domains\Blog\Rules\ArticleCategoryPermission::ARTICLE_CATEGORY_INDEX,
                \App\Domains\Blog\Rules\ArticleCategoryPermission::ARTICLE_CATEGORY_CREATE,
                \App\Domains\Blog\Rules\ArticlePermission::ARTICLE_INDEX,
                \App\Domains\Blog\Rules\ArticlePermission::ARTICLE_CREATE,
                \App\Domains\Blog\Rules\QuotePermission::QUOTE_INDEX,
                \App\Domains\Blog\Rules\QuotePermission::QUOTE_CREATE
                ])
{{--                <li>--}}
{{--                    <a href="{{ url($admin_base_url . "/article-category") }}"--}}
{{--                       class="{{ in_array(request()->path(), ['admin/article-category', 'admin/quote', 'admin/article']) ? "active" : "" }}">--}}
{{--                        <i class="fas fa-blog"></i>--}}
{{--                        <span> @lang('lang.blog') </span>--}}
{{--                        <span class="menu-arrow"></span>--}}
{{--                    </a>--}}
{{--                    <ul class="nav-second-level nav" aria-expanded="false">--}}
{{--                        @include('blog::admin.article-category.menu')--}}
{{--                        @include('blog::admin.quote.menu')--}}
{{--                        @include('blog::admin.article.menu')--}}
{{--                    </ul>--}}
{{--                </li>--}}
                @endpermitted

{{--                @permitted([App\Domains\Partner\Rules\PartnerPermissions::PARTNER_INDEX,--}}
{{--                App\Domains\Partner\Rules\PartnerPermissions::PARTNER_CREATE])--}}
{{--                @include('partner::admin.partner.menu')--}}
{{--                @endpermitted--}}

				@permitted([App\Domains\Workshop\Rules\WorkshopPermission::WORKSHOP_INDEX, App\Domains\Workshop\Rules\WorkshopPermission::WORKSHOP_CREATE])
{{--                        @include('workshop::admin.workshop.menu')--}}
                @endpermitted


                @permitted([\App\Domains\Reports\Rules\SummaryReportPermission::SUMMARY_REPORT_INDEX,
                \App\Domains\Reports\Rules\LessonReportPermission::LESSON_REPORT_INDEX,\App\Domains\Reports\Rules\GlobalKnowledgeReportPermission::KNOWLEDGE_REPORT_INDEX])
{{--                <li>--}}
{{--                    <a href="{{ url($admin_base_url . "/lesson-report?video_id=null") }}"--}}
{{--                       class="{{ in_array(request()->path(), ['admin/lesson-report', 'admin/summary-report', 'admin/global-knowledge-report']) ? "active" : "" }}">--}}
{{--                        <i class="fas fa-chart-pie"></i>--}}
{{--                        <span> @lang('lang.report') </span>--}}
{{--                        <span class="menu-arrow"></span>--}}
{{--                    </a>--}}
{{--                    <ul class="nav-second-level nav" aria-expanded="false">--}}

{{--                        @include('reports::admin.lesson-report.menu')--}}
{{--                        @include(--}}
{{--                            'reports::admin.global-knowledge-report.menu'--}}
{{--                        )--}}

{{--                        @include('reports::admin.summary-report.menu')--}}


{{--                    </ul>--}}
{{--                </li>--}}

                @endpermitted

                @permitted([\App\Domains\Voucher\Rules\VoucherPermission::VOUCHER_INDEX,
                \App\Domains\Voucher\Rules\VoucherPermission::VOUCHER_CREATE])
                @include('voucher::admin.voucher.menu')
                @endpermitted

                @include('contact_us::admin.contact-us.menu')
                @include('faq::admin.faq.menu')

                @include('payments::admin.subscription-cancellation.menu')



                <hr style="border-top: 2px solid #333336" class="mt-4">



                @permitted([\App\Domains\Admin\Rules\AdminPermission::ADMIN_INDEX, \App\Domains\Admin\Rules\AdminPermission::ADMIN_CREATE,
                \App\Domains\Admin\Rules\RolesPermission::ROLE_INDEX, \App\Domains\Admin\Rules\RolesPermission::ROLE_CREATE,
                \App\Domains\UserActivity\Rules\UserActivityPermission::USER_ACTIVITY_INDEX])
                <li>
                    <a href="{{url($admin_base_url . "/admin")}}"
                       class="{{ in_array(request()->path(), ['admin/admin', 'admin/role', 'admin/user-activity',  'admin/proctor-user']) ? "active" : "" }}">
                        <i class="fas fa-user-secret"></i>
                        <span> @lang('admin::lang.admins_management') </span>
                        {{--                        <span class="menu-arrow"></span>--}}
                    </a>
                    {{--                    <ul class="nav-second-level nav">--}}
                    {{--                        @include('admin::admin.admin.menu')--}}
                    {{--                        @include('admin::admin.role.menu')--}}
                    {{--                        @include('UserActivity::menu')--}}

                    {{--                    </ul>--}}
                </li>
                @endpermitted



                @permitted([
{{--                \App\Domains\User\Rules\GoalPermission::GOAL_INDEX,--}}
{{--                \App\Domains\User\Rules\GoalPermission::GOAL_CREATE,--}}
                \App\Domains\Course\Rules\CourseCategoryPermission::COURSE_CATEGORY_INDEX,
                \App\Domains\Course\Rules\CourseCategoryPermission::COURSE_CATEGORY_CREATE,
                ])
                <!-- One New tab:Onboarding -->
                <li>
                    <a href="{{ url($admin_base_url . "/course-category") }}"
                       class="{{ in_array(request()->path(), ['admin/course-category']) ? "active" : "" }}">
                        <i class="fas fa-folder-open"></i>
                        <span> @lang('Content Structure') </span>
                        {{--                        <span class="menu-arrow"></span>--}}
                    </a>
                    {{--                    <ul class="nav-second-level nav" aria-expanded="false">--}}
                    {{--                        @include('course::admin.course-category.menu')--}}
                    {{--                        @include('user::admin.goal.menu')--}}


                    {{--                    </ul>--}}
                </li>
                <!-- One New tab -->
                @endpermitted

                @if(auth('admin')->user()->is_super_admin)
                    <li>
                        <a href="{{url($admin_base_url . "/sitemap/generate-site-map")}}">
                            <i class="fas fa-map-marked"></i>
                            <span> @lang('Generate Sitemap') </span>
                        </a>
                    </li>
                @endif
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
