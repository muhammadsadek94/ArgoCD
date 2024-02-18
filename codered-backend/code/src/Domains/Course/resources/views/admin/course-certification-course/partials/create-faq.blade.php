<div id="faq" class="col-12">
	<h2>FAQ</h2>
	<div class="row faq-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'faq[question][]',
				'label' => trans("Question"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
			@include('admin.components.inputs.textarea', [
				'name' => 'faq[answer][]',
				'label' => trans("Answer"),
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
					data-duplicate="#faq > .row"
					data-target="#faq-multiple"
					data-remove=".faq-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
