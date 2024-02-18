
@permitted([\App\Domains\ContactUs\Rules\ContactUsPermission::CONTACTUS_INDEX])
<li>
    <a href="{{ url($admin_base_url . "/contact-us") }}"
       class="{{ in_array(request()->path(), ['admin/contact-us']) ? "active" : "" }}">
        <i class="fas fa-phone"></i>
        <span> @lang('contact_us::lang.contact_us') </span>
    </a>
</li>
@endpermitted

