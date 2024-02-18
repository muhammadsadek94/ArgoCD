<div id="features" class="col-12  px-0">
	<h2>Add Royalties</h2>
	<div class="row  features-item">
		<div class="col-3">
			@include('admin.components.inputs.number', [
				'name' => 'advances[]',
				'label' => "Outstanding advances",
				'form_options'=> [''],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-3">
			@include('admin.components.inputs.number', [
				'name' => 'royalties[]',
				'label' => "Royalty",
				'form_options'=> [''],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-3">
			@include('admin.components.inputs.number', [
				'name' => 'royalties_carried_out[]',
				'label' => "Royalties Carried Out From Last Quarter",
				'form_options'=> [''],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-5">
		@include('admin.components.inputs.select', [
			'name' => 'courses[]',
			'label' => trans("Courses to be Added to Bundle"),
			'cols' => 'col-lg-6 col-12 courses-input',
			'select_options' =>  $courses,
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
