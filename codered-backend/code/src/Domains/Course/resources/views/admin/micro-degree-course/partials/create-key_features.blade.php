<div id="key_features" class="col-12">
	<h2>Key Features</h2>
	<div class="row key_features-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'key_features[]',
				'label' => trans("Skill"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#key_features > .row"
					data-target="#key_features-multiple"
					data-remove=".key_features-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
