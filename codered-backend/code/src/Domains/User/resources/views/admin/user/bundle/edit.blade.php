<!-- Button trigger modal -->
<a type="button" class="btn btn-edit text-white" data-toggle="modal" data-target="#editbundle-{{ $subscription->id }}"><i class="fa fa-pen"></i>
	Edit
</a>

<!-- Modal -->
<div class="modal fade" id="editbundle-{{ $subscription->id}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Bundle/Pro Access</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			{!! Form::model($subscription, ['method'=>'PATCH','url' => "{$route}/actions/bundle/{$subscription->id}", 'files'=>true,'data-toggle'=> 'ajax', 'data-refresh-page' => "true"]) !!}

			<div class="modal-body">
				<div class="container-fluid">
					@include('user::admin.user.bundle.form')
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success">Save</button>
			</div>
			{!! Form::close() !!}

		</div>
	</div>
</div>
