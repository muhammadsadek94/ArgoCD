<div id="metadata" class="col-12">
	<h4>Metadata</h4>
	<div class="row metadata-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'metadata[name][]',
				'label' => trans("Name"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
			@include('admin.components.inputs.textarea', [
				'name' => 'metadata[content][]',
				'label' => trans("Content"),
				'form_options'=> [
					'required',
					'rows' => 5
				],
				'cols' => 'col-12',
			])
		</div>
		{{-- <div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#metadata > .row"
					data-target="#metadata-multiple-create"
					data-remove=".metadata-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div> --}}
	</div>
</div>
<div id="metadata-multiple-create" class="col-12 px-0">
</div>
