<div id="features" class="col-12">
	<h2>features</h2>
	<div class="row features-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'features[]',
				'label' => trans("features"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#features > .row"
					data-target="#features-multiple-create"
					data-remove=".features-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
<div id="features-multiple-create" class="col-12 px-0">
</div>
