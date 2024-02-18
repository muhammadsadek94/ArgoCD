<div id="what-learn" class="col-12">
	<h2>What will you learn?</h2>
	<div class="row what-learn-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'learn_title[]',
				'label' => trans("Title"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
			@include('admin.components.inputs.textarea', [
				'name' => 'learn_description[]',
				'label' => trans("Description"),
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
			    'value' => $row->image_id ?? null,
			    'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
			    'endpoint' => url(Constants::ADMIN_BASE_URL . '/course/actions/upload-image'),
			    'form_options' => ['required']
			])

		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#what-learn > .row"
					data-target="#what-learn-multiple"
					data-remove=".what-learn-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
