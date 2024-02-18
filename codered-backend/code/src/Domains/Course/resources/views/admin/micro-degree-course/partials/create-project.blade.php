<div id="project" class="col-12">
	<h2>project</h2>
	<div class="row project-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'project[title][]',
				'label' => trans("Title"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
			@include('admin.components.inputs.textarea', [
				'name' => 'project[description][]',
				'label' => trans("Description"),
				'form_options'=> [
					'required',
					'rows' => 2
				],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#project > .row"
					data-target="#project-multiple"
					data-remove=".project-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
