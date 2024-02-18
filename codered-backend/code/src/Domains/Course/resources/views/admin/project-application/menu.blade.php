
<li>
    <a href="{{ url($admin_base_url . "/application-project") }}"
       class="{{ in_array(request()->path(), ['admin/application-project']) ? "active" : "" }}">
        <i class="fas fa-bullseye"></i>
        <span> Project Applications </span>
    </a>
</li>
