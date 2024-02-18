<div id="flags" class="col-12">
	<h2>Flags</h2>
	<div class="row flags-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'flags[title][]',
				'label' => trans("Sub Title"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
			@include('admin.components.inputs.textarea', [
				'name' => 'flags[description][]',
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
					data-toggle="duplicate-input-custom"
					data-duplicate="#flags > .row"
					data-target="#flags-multiple"
					data-remove=".flags-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
