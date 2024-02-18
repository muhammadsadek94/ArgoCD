<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createLessonResource">
	Create
</button>

<!-- Modal -->
<div class="modal fade" id="createLessonResource" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Resource</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{!! Form::open(['method'=>'POST','url' => "{$route}/actions/resource", 'files'=>true,'data-toggle'=> 'ajax', 'data-refresh-page' => "true"]) !!}

			<div class="modal-body">
				<div class="container-fluid">
					@include('course::admin.lesson.resource.form')
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			{!! Form::close() !!}

		</div>
	</div>
</div>
