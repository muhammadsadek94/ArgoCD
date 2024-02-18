{!! Form::open(['method' => 'DELETE', 'url' => [$route, $id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $id) !!}

@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['edit'])))
<a data-toggle="tooltip" data-placement="top" title="Edit" href="{{ url("{$route}/{$id}/edit") }}" class="btn btn-icon btn-sm mr-1">
    <i class="fas fa-pen"></i>
</a>
@endif


@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['index'])))
<a data-toggle="tooltip" data-placement="top" title="Chapters" href="{{ url(Constants::ADMIN_BASE_URL . "/chapter?course_id={$row->id}") }}" class="btn btn-icon btn-sm mr-1">
    <i class="fa fa-book"></i>
</a>
@endif

@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['create'])))
<br><br>
<a data-toggle="tooltip" data-placement="top" title="Final Assessment" href="{{ url(Constants::ADMIN_BASE_URL . "/course-assessment?course_id={$row->id}") }}" class="btn btn-icon btn-sm mr-1">
    <i class="fa fa-eye"></i>
</a>
@endif


@if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['create'])))
<a data-toggle="tooltip" data-placement="top" title="Duplicate" href="{{ url(Constants::ADMIN_BASE_URL . "/course/duplicate?course_id={$row->id}") }}" class="btn btn-icon btn-sm mr-1 loading">
    <i class="fa fa-plus"></i>
</a>
@endif




@if(isset($showable) && ($showable == true || $showable == 1))
<a data-toggle="tooltip" data-placement="top" title="View" href="{{ url("{$route}/{$id}") }}" class="btn btn-icon btn-sm mr-1">
    <i class="fas fa-eye"></i>
</a>
@endif

@if(isset($permitted_actions) && ($permitted_actions['delete'] == null || is_permitted($permitted_actions['delete'])))
    <button data-toggle="tooltip" data-placement="top" title="Delete" type="submit"  class="btn btn-icon btn-sm mr-1"
        onclick="return confirm(' Important Note: If user want to delete added by enterprise with license the license will be back to the enterprise, Confirm Delete operation ?');">
        <i class="fas fa-trash"></i>
    </button>
@endif



{{-- @if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['create'])))
    <a data-toggle="tooltip" data-placement="top" title="Add Chapter" href="{{ url(Constants::ADMIN_BASE_URL . "/chapter/create?course_id={$row->id}") }}" class="btn btn-icon btn-sm mr-1">
        <i class="fas fa-plus-square"></i>
    </a>
@endif --}}



{{-- @if(isset($permitted_actions) && ($permitted_actions['edit'] == null || is_permitted($permitted_actions['create'])))
<a data-toggle="tooltip" data-placement="top" title="Add Questions" href="{{ url(Constants::ADMIN_BASE_URL . "/course-assessment/create?course_id={$row->id}") }}" class="btn btn-icon btn-sm mr-1">
    <i class="fas fa-question"></i>
</a>
@endif
 --}}




{!! Form::close() !!}
