@permitted([\App\Domains\Blog\Rules\ArticlePermission::ARTICLE_INDEX, \App\Domains\Blog\Rules\ArticlePermission::ARTICLE_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="fas fa-newspaper"></i>
		<span> @lang('blog::lang.article') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
			@permitted(\App\Domains\Blog\Rules\ArticlePermission::ARTICLE_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/article") }}">@lang("lang.Index")</a>
			</li>
			@endpermitted

			@permitted(\App\Domains\Blog\Rules\ArticlePermission::ARTICLE_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/article/create") }}">@lang("lang.create" )</a>
			</li>
			@endpermitted
	</ul>
</li>
@endpermitted


