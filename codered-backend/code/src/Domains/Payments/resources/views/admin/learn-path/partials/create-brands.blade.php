<div id="brands" class="col-12">
	<h2>brands</h2>
	<div class="row brands-item">
		<div class="col-11">
			@include('admin.components.inputs.file-ajax', [
				'name' => 'brands[]',
				'label' => trans("brands"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#brands > .row"
					data-target="#brands-multiple-create"
					data-remove=".brands-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
<div id="brands-multiple-create" class="col-12 px-0">
</div>
