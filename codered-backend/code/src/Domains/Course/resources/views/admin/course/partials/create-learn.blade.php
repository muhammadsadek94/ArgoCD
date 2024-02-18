<div id="what-learn" class="col-12">
	<h4>What will you learn?</h4>
	<div class="row description-feature-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'learn[]',
				'label' => trans("Feature"),
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
					data-duplicate=".description-feature-item"
					data-target="#description-feature-multiple"
					data-remove=".description-feature-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-danger btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
