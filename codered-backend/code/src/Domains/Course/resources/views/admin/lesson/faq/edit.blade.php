<!-- Button trigger modal -->
<a type="button" class="btn btn-info text-white" data-toggle="modal" data-target="#editFaq-{{ $faq->id }}">
	Edit
</a>

<!-- Modal -->
<div class="modal fade" id="editFaq-{{ $faq->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Faq</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{!! Form::model($faq, ['method'=>'PATCH','url' => "{$route}/actions/faq/{$faq->id}", 'files'=>true,'data-toggle'=> 'ajax', 'data-refresh-page' => "true"]) !!}
			
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
