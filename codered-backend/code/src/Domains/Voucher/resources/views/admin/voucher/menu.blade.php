@permitted([\App\Domains\Voucher\Rules\VoucherPermission::VOUCHER_INDEX, \App\Domains\Voucher\Rules\VoucherPermission::VOUCHER_CREATE])
<li>
    <a href="{{ url($admin_base_url . "/voucher") }}">
        <i class="fas fa-ticket-alt"></i>
        <span> @lang('Vouchers') </span>
    </a>
</li>
@endpermitted

