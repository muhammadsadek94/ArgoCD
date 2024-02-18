@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['edit'])))
    <a data-toggle="tooltip" data-placement="top" title="Edit"  href="{{ url("{$route}/{$id}/edit") }}"
       class="btn btn-icon btn-sm mr-1">
        <i class="fa fa-pen"></i>
    </a>
@endif
