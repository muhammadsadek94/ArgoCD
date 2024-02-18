<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="">{{ $page_title }}</h4>
            <div>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url("{$admin_base_url}/dashboard") }}">@lang('lang.dashboard')</a></li>
                    @foreach($crumbs as $crumb)
                        @php
                            if(gettype($crumb) != 'object')
                                $crumb = (object)$crumb;

                        @endphp

                        <li class="breadcrumb-item {{ ($crumb->active ?? false) ? 'active' : null }} ">
                            <a {{ isset($crumb->url) && !empty($crumb->url) ? "href=" . url($crumb->url) : null }}>{{ $crumb->title }}</a>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>

