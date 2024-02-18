<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createLessonFaq">
	Create
</button>

<!-- Modal -->
<div class="modal fade" id="createLessonFaq" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Create Faq</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{!! Form::open(['method'=>'POST','url' => "{$route}/actions/faq", 'files'=>true,'data-toggle'=> 'ajax', 'data-refresh-page' => "true"]) !!}
			
			<div class="modal-body">
				<div class="container-fluid">
					@include('course::admin.lesson.faq.form')
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
