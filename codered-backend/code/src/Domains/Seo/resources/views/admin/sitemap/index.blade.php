<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    @foreach($urls as $url)
        <url>
            <loc>{{ $url['url'] }}</loc>
            <lastmod>{{ $url['date'] }}</lastmod>
        </url>
    @endforeach

</urlset>
