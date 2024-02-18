@permitted([\App\Domains\UserActivity\Rules\UserActivityPermission::USER_ACTIVITY_INDEX])
<li>
    <a href="{{ url("{$admin_base_url}/user-activity") }}">
        <i class="fas fa-video"></i>
        <span> @lang('Users Activity') </span>
    </a>
</li>
@endpermitted
