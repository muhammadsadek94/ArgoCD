<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createLessonCaption">
	Create
</button>


<!-- Modal -->
<div class="modal fade" id="createLessonCaption" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
	 aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Create Caption</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            {!! Form::model($row,['method'=>'POST','url' => [$route, $row->id,'vimeo', 'upload', 'caption'], 'files'=>true,'data-toggle'=> 'ajax','data-refresh-page' => "true"]) !!}
            {!! Form::hidden('course_id', $row->course_id) !!}
            {!! Form::hidden('chapter_id', $row->chapter_id) !!}
            {!! Form::hidden('id', $row->id) !!}

            <div class="modal-body">
				<div class="container-fluid">
					@include('course::admin.lesson.video.captions.form')
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

@push('script-bottom')
<script>
    $.fn.modal.Constructor.prototype._enforceFocus = function() {};
</script>
@endpush

