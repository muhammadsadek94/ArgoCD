@permitted([\App\Domains\User\Rules\PayoutPermission::PAYOUT_INDEX, \App\Domains\User\Rules\PayoutPermission::PAYOUT_CREATE])
<li>
    <a href="{{ url($admin_base_url . "/payout") }}">
        <i class="fas fa-bullseye"></i>
        <span> @lang('Payout') </span>
    </a>
</li>
@endpermitted

