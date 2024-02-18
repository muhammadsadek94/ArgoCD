<div id="what-learn" class="col-12">
	<h2>What will you learn?</h2>
	<div class="row what-learn">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'learn[]',
				'label' => trans("learn"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
    		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#what-learn > .row"
					data-target="#what-learn-multiple-create"
					data-remove=".what-learn-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
<div id="what-learn-multiple-create" class="col-12 px-0">
</div>
