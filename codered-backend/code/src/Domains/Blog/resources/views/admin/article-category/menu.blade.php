@permitted([\App\Domains\Blog\Rules\ArticleCategoryPermission::ARTICLE_CATEGORY_INDEX, \App\Domains\Blog\Rules\ArticleCategoryPermission::ARTICLE_CATEGORY_CREATE])
<li>
	<a href="javascript: void(0);">
		<i class="far fa-newspaper"></i>
		<span> @lang('blog::lang.article_category') </span>
		<span class="menu-arrow"></span>
	</a>
	<ul class="nav-third-level nav" aria-expanded="false">
			@permitted(\App\Domains\Blog\Rules\ArticleCategoryPermission::ARTICLE_CATEGORY_INDEX)
			<li>
				<a href="{{ url($admin_base_url . "/article-category") }}">@lang("lang.Index")</a>
			</li>
			@endpermitted

			@permitted(\App\Domains\Blog\Rules\ArticleCategoryPermission::ARTICLE_CATEGORY_CREATE)
			<li>
				<a href="{{ url($admin_base_url . "/article-category/create") }}">@lang("lang.create" )</a>
			</li>
			@endpermitted
	</ul>
</li>
@endpermitted

