<a data-toggle="tooltip" data-placement="top" title="View" href="{{ url("{$route}/{$id}") }}" class="btn btn-icon btn-sm mr-1">
	<i class="fas fa-eye"></i>
</a>

@if(isset($permitted_actions) && ($permitted_actions['delete'] == null || is_permitted($permitted_actions['delete'])))
    {!! Form::open(['method' => 'DELETE', 'url' => [$route, $id], 'class' => 'form-horizontal d-inline-block']) !!}
    {!! Form::hidden('id', $id) !!}
        <button data-toggle="tooltip" data-placement="top" title="Delete" type="submit"  class="btn btn-icon btn-sm mr-1"
                onclick="return confirm(' Are you sure you want to delete ?');">
            <i class="fas fa-trash"></i>
        </button>
    {!! Form::close() !!}
@endif

@permitted([\App\Domains\User\Rules\PayoutPermission::PAYOUT_EDIT])

@if($row->status == \App\Domains\User\Enum\PayoutStatus::PENDING)
@permitted([\App\Domains\User\Rules\PayoutPermission::PAYOUT_APPROVE])

    <a href="{{ url("{$route}/actions/{$row->id}/approve") }}"
       onclick="return confirm('Do you sure mark this request as approved?')"
       class="btn btn-icon btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Approve">
        <i class="fa fa-check"></i>
    </a>

    @endpermitted
    @permitted([\App\Domains\User\Rules\PayoutPermission::PAYOUT_EDIT])

    <a href="{{ url("{$route}/{$row->id}/edit") }}"
       class="btn btn-icon btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Edit">
        <i class="fa fa-pen"></i>
    </a>
    @endpermitted
    @permitted([\App\Domains\User\Rules\PayoutPermission::PAYOUT_DISAPPROVE])

        <a href="{{ url("{$route}/actions/{$row->id}/disapprove") }}"
	        onclick="return confirm('Do you sure mark this request as disapproved?')"
            class="btn btn-icon btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Disapprove"> <i class="fa fa-times-circle"></i> </a>
    @endpermitted
@elseif($row->status == \App\Domains\User\Enum\PayoutStatus::APPROVED)
    @permitted([\App\Domains\User\Rules\PayoutPermission::PAYOUT_PAID])
        <a href="{{ url("{$route}/actions/{$row->id}/paid") }}"
        class="btn btn-icon btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Paid"
         onclick="return confirm('Do you sure mark this request as paid?')"> <i class="fa fa-check-double"></i></a>
    @endpermitted

@else

@endif

@if(isset($row->attachment->full_url))

                        <a target="_blank"  data-toggle="tooltip" data-placement="top" title="Download PDF" href="/admin/payout/actions/{{ $row->id }}/export" class="btn btn-icon btn-sm mr-1">
                        <i class="fas fa-download"> </i>
                        </a>


@endif
@endpermitted
