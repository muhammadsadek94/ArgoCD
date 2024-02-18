@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['edit'])))
<a data-toggle="tooltip" data-placement="top" title="Edit" href="{{ url("{$route}/{$id}/edit") }}" class="btn btn-icon btn-sm mr-1">
    <i class="fas fa-pen"></i>
</a>
@endif

@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['create'])))
<a data-toggle="tooltip" data-placement="top" title="Duplicate" href="{{ url(Constants::ADMIN_BASE_URL . "/payment-integration/duplicate?payment_integration={$row->id}") }}" 
    class="btn btn-icon btn-sm mr-1">
    <i class="fas fa-plus"></i>
</a>
@endif
