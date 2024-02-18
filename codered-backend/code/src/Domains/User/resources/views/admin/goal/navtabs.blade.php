<ul class="nav">

    @permitted([\App\Domains\Course\Rules\CourseCategoryPermission::COURSE_CATEGORY_INDEX])
        <a href="{{ url($admin_base_url . "/course-category") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
            <span data-link="{{ url($admin_base_url . "/course-category") }}" class="fs-1 text-black px-1" >Course Category</span>
        </a>
    @endpermitted

    @permitted(\App\Domains\User\Rules\GoalPermission::GOAL_INDEX)
    <a  href="{{ url($admin_base_url . "/goal") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
        <span span data-link="{{ url($admin_base_url . "/goal") }}" class="fs-1 text-black px-1" >Goals</span>
    </a>
    @endpermitted

</ul>
