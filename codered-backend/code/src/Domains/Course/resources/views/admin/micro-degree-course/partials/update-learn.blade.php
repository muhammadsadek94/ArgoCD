@isset($row)
	<h2>What you will learn ?</h2>
	@foreach($row->course_learn()->get() as $value )

		<div class="row what-learn-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'learn_title[]',
					'label' => trans("Title"),
					'value' => $value->title,
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				@include('admin.components.inputs.textarea', [
					'name' => 'learn_description[]',
					'label' => trans("Description"),
					'value' => $value->description,
					'form_options'=> [
						'required',
						'rows' => 2
					],
					'cols' => 'col-12',
				])
				
				@include('admin.components.inputs.file-ajax', [
					'name' => 'learnimage_id',
					'label' => trans('Image'),
					'cols' => 'col-12',
					'value' => $value->image_id ?? null,
			    	'placeholder' => isset($value->image) ? asset("{$value->image->full_url}") : null,
					'endpoint' => url(Constants::ADMIN_BASE_URL . '/course/actions/upload-image'),
					'form_options' => ['required']
				])
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input"
							data-duplicate=".what-learn-item"
							data-target="#what-learn-multiple"
							data-remove=".what-learn-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#what-learn > .row"
							data-target="#what-learn-multiple"
							data-remove=".what-learn-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
