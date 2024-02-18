<div id="prerequisite" class="col-12">
	<h2>prerequisite</h2>
	<div class="row prerequisite-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'prerequisite[]',
				'label' => trans("prerequisite"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#prerequisite > .row"
					data-target="#prerequisite-multiple-create"
					data-remove=".prerequisite-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
<div id="prerequisite-multiple-create" class="col-12 px-0">
</div>
