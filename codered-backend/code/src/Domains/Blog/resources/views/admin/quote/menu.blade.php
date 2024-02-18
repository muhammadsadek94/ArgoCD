@permitted([\App\Domains\Blog\Rules\QuotePermission::QUOTE_INDEX, \App\Domains\Blog\Rules\QuotePermission::QUOTE_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-quote-left"></i>
		<span> @lang('blog::lang.quote') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
			@permitted(\App\Domains\Blog\Rules\QuotePermission::QUOTE_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/quote") }}">@lang("lang.Index")</a>
			</li>
			@endpermitted

			@permitted(\App\Domains\Blog\Rules\QuotePermission::QUOTE_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/quote/create") }}">@lang("lang.create" )</a>
			</li>
			@endpermitted
	</ul>
</li>
@endpermitted


