<ul class="pagination pagination-rounded justify-content-end my-2">
    {{  $rows->appends(request()->all() ??[])->links('pagination::bootstrap-4') }}
</ul>
