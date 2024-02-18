<ul class="nav">

    @permitted(\App\Domains\Blog\Rules\ArticleCategoryPermission::ARTICLE_CATEGORY_INDEX)
    <a  href="{{ url($admin_base_url . "/article-category") }}" id="nav-tab" class="nav-item tabs-border top-left-border-radius text-decoration-none bg-white px-4 py-2">
        <span span data-link="{{ url($admin_base_url . "/article-category") }}" class="fs-1 text-black px-1" >@lang('blog::lang.article_category')</span>
    </a>
    @endpermitted


    @permitted(\App\Domains\Blog\Rules\QuotePermission::QUOTE_INDEX)
    <a href="{{ url($admin_base_url . "/quote") }}" id="nav-tab" class="nav-item tabs-border text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/quote") }}" class="fs-1 text-black px-1">@lang('blog::lang.quote')</span>
    </a>
    @endpermitted

    @permitted(\App\Domains\Blog\Rules\ArticlePermission::ARTICLE_INDEX)
    <a href="{{ url($admin_base_url . "/article") }}" id="nav-tab" class="nav-item tabs-border top-right-border-radius text-decoration-none bg-white px-4 py-2">
        <span data-link="{{ url($admin_base_url . "/article") }}" class="fs-1 text-black px-1" >@lang('blog::lang.article')</span>
    </a>
    @endpermitted

</ul>
