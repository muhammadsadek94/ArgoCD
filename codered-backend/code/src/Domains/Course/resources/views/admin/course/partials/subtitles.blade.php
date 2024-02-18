@isset($row->subtitles)
	<h4>Course Subtitles</h4>
	@foreach($row->subtitles as $subtitle)
		<div class="row subtitles">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'subtitles[]',
					'label' => 'Subtitle',
					'value' => $subtitle,
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input"
							data-duplicate=".subtitles"
							data-target="#subtitles-multiple"
							data-remove=".subtitles"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#subtitles > .row"
							data-target="#subtitles-multiple"
							data-remove=".subtitles"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@else
	<h4>Course Subtitles</h4>
	<div class="row subtitles">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'subtitles[]',
				'label' => 'Subtitle',
				'value' => null,
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate=".subtitles"
					data-target="#subtitles-multiple"
					data-remove=".subtitles"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-danger btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
@endisset
